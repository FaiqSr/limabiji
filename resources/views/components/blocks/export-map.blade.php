@props([
    'data'               => [],
    'locale'             => 'en',
    'exportDestinations' => null,  // Eloquent collection from controller
])

@php
    $hidden   = $data['field_hidden'] ?? [];
    $heading  = $data['heading']  ?? ($locale === 'id' ? 'Peta Ekspor' : 'Export Experience');
    $subtitle = $data['subtitle'] ?? ($locale === 'id' ? 'Jelajahi jangkauan global dan komitmen kami terhadap kualitas' : 'Explore our global reach and commitment to quality');

    // Build locations array for OpenLayers (Primary source: database ExportDestination model)
    $destList = (!empty($exportDestinations) && count($exportDestinations) > 0) ? $exportDestinations : ($data['items'] ?? []);
    if (is_string($destList)) {
        $destList = json_decode($destList, true) ?: [];
    }
    $mapLocations = array_map(function($dest) use ($locale) {
        return [
            'name'        => is_object($dest) ? $dest->getNameForLocale($locale) : ($dest['name'] ?? ''),
            'code'        => is_object($dest) ? $dest->country_code              : ($dest['country_code'] ?? 'jp'),
            'coords'      => is_object($dest) ? [$dest->longitude, $dest->latitude] : [floatval($dest['longitude'] ?? 0), floatval($dest['latitude'] ?? 0)],
            'description' => is_object($dest) ? $dest->getDescriptionForLocale($locale) : ($dest['description'] ?? ''),
        ];
    }, is_array($destList) ? $destList : (is_object($destList) && method_exists($destList, 'all') ? $destList->all() : []));
@endphp

@once
@push('modules')
    <script src="https://unpkg.com/ol/dist/ol.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/ol/ol.css" />
    <script src="https://unpkg.com/ol-mapbox-style/dist/olms.js"></script>
@endpush
@push('styles')
    <style>
        #map {
            width: 100%;
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .ol-popup {
            position: absolute;
            background-color: #FFFFFF;
            color: #1a1a1a;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            padding: 16px;
            border-radius: 16px;
            border: 1px solid #E5E2DC;
            bottom: 20px;
            left: -120px;
            width: 260px;
            pointer-events: auto;
            z-index: 100;
        }
        .ol-popup:after, .ol-popup:before {
            top: 100%; border: solid transparent; content: " ";
            height: 0; width: 0; position: absolute; pointer-events: none;
        }
        .ol-popup:after {
            border-top-color: #1e1e1e; border-width: 10px;
            left: 120px; margin-left: -10px;
        }
        .ol-popup-closer {
            text-decoration: none; position: absolute;
            top: 8px; right: 12px; color: #9ca3af; font-weight: bold; font-size: 16px;
        }
        .ol-popup-closer:hover { color: #1a1a1a; }
        .reset-view-btn {
            position: absolute; top: 15px; right: 15px; z-index: 10;
            background-color: #FFFFFF; color: #1a1a1a; border: 1px solid #E5E2DC;
            padding: 14px 14px; border-radius: 12px; font-size: 14px; font-weight: 600;
            cursor: pointer; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            display: flex; align-items: center; gap: 6px; transition: all 0.2s ease;
        }
        .reset-view-btn:hover {
            background-color: #2a2a2a; border-color: #555; transform: translateY(-1px);
        }
    </style>
@endpush
@endonce

<div class="container m-auto px-5 relative my-20">
    @if(empty($hidden['heading']) && $heading)
        <x-section-heading :title="$heading" :subtitle="empty($hidden['subtitle']) ? $subtitle : null" />
    @endif
    <div class="relative">
        <div id="map"></div>

        <button id="btn-reset-map" class="reset-view-btn" title="Kembali ke posisi awal">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                <path d="M3 3v5h5" />
            </svg>
        </button>

        <div id="popup" class="ol-popup">
            <a href="#" id="popup-closer" class="ol-popup-closer">&times;</a>
            <div id="popup-content"></div>
        </div>
    </div>
</div>

@once
@push('scripts')
<script>
(function() {
    const initialCenter = [70.0, 10.0];
    const initialZoom = 2.3;

    const openfreemap = new ol.layer.Group();

    const locations = {!! json_encode($mapLocations) !!};

    const features = locations.map(loc => {
        const feature = new ol.Feature({
            geometry: new ol.geom.Point(ol.proj.fromLonLat(loc.coords)),
            name: loc.name,
            description: loc.description,
        });
        feature.setStyle(new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 0.5],
                src: `https://flagcdn.com/w40/${loc.code}.png`,
                scale: 0.85,
            }),
        }));
        return feature;
    });

    const coffeeLayer = new ol.layer.Vector({
        source: new ol.source.Vector({ features: features }),
    });

    const controls = typeof ol.control.defaults === 'function'
        ? ol.control.defaults({ zoom: false })
        : ol.control.defaults.defaults({ zoom: false });

    const container = document.getElementById('popup');
    const content = document.getElementById('popup-content');
    const closer = document.getElementById('popup-closer');

    if (!container || !content || !closer) return;

    const overlay = new ol.Overlay({
        element: container,
        autoPan: { animation: { duration: 250 } },
    });

    closer.onclick = function () {
        overlay.setPosition(undefined);
        closer.blur();
        return false;
    };

    const view = new ol.View({
        center: ol.proj.fromLonLat(initialCenter),
        zoom: initialZoom,
        minZoom: 1.5,
    });

    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    const map = new ol.Map({
        controls: controls,
        layers: [openfreemap, coffeeLayer],
        overlays: [overlay],
        view: view,
        target: 'map',
    });

    olms.apply(openfreemap, 'https://api.maptiler.com/maps/019ff310-e37e-78d9-a360-edec8b678276/style.json?key=F7vo4PTXSgkVkQxxM3gY');

    map.on('singleclick', function (evt) {
        const feature = map.forEachFeatureAtPixel(evt.pixel, function (feat) { return feat; });
        if (feature) {
            const coordinates = feature.getGeometry().getCoordinates();
            const name = feature.get('name');
            const description = feature.get('description');
            content.innerHTML = `<h3 class="font-bold text-lg text-primary mb-1">${name}</h3><p class="text-sm text-gray-300 leading-snug">${description}</p>`;
            overlay.setPosition(coordinates);
        } else {
            overlay.setPosition(undefined);
        }
    });

    map.on('pointermove', function (e) {
        const pixel = map.getEventPixel(e.originalEvent);
        const hit = map.hasFeatureAtPixel(pixel);
        map.getTargetElement().style.cursor = hit ? 'pointer' : '';
    });

    const resetBtn = document.getElementById('btn-reset-map');
    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            overlay.setPosition(undefined);
            view.animate({ center: ol.proj.fromLonLat(initialCenter), zoom: initialZoom, duration: 600 });
        });
    }
})();
</script>
@endpush
@endonce
