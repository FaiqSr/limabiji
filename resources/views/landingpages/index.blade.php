@extends('layouts.landingpages')

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

        /* Styling Popup Marker */
        .ol-popup {
            position: absolute;
            background-color: #1e1e1e;
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            padding: 16px;
            border-radius: 16px;
            border: 1px solid #333;
            bottom: 20px;
            left: -120px;
            width: 260px;
            pointer-events: auto;
            z-index: 100;
        }

        .ol-popup:after,
        .ol-popup:before {
            top: 100%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .ol-popup:after {
            border-top-color: #1e1e1e;
            border-width: 10px;
            left: 120px;
            margin-left: -10px;
        }

        .ol-popup-closer {
            text-decoration: none;
            position: absolute;
            top: 8px;
            right: 12px;
            color: #9ca3af;
            font-weight: bold;
            font-size: 16px;
        }

        .ol-popup-closer:hover {
            color: #ffffff;
        }

        /* Styling Tombol Reset View */
        .reset-view-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
            background-color: #1e1e1e;
            color: #ffffff;
            border: 1px solid #333;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .reset-view-btn:hover {
            background-color: #2a2a2a;
            border-color: #555;
            transform: translateY(-1px);
        }
    </style>
@endpush

@section('content')
    <div class="container m-auto px-5">
        <div class="text-center pt-10 pb-10">
            <h2 class="text-2xl lg:text-4xl text-white " id="hero-text">LIMA BIJI AGRITECH</h2>
            <h1 class="text-6xl sm:text-7xl lg:text-9xl alfa-slab-one-regular mt-10 text-white" id="speciality">
                SPECIALITY<br />ENZYMATIC<br />CIVET
            </h1>
        </div>
        <div>
            <div class="flex sm:flex-row flex-col justify-center items-center gap-5 ">
                <a href=""
                    class="text-white bg-primary px-7 py-3 rounded-4xl font-bold text-xl text-center w-full sm:w-fit">View
                    Product</a>
                <a href=""
                    class="font-bold text-white border border-white px-7 py-3 rounded-4xl text-xl text-center w-full sm:w-fit">News</a>
                <a
                    href=""class="font-bold text-white border border-white px-7 py-3 rounded-4xl text-xl text-center w-full sm:w-fit">Contact</a>
            </div>
        </div>
    </div>
    <div class="container m-auto px-5 pb-20 pt-40">
        <div class="grid grid-cols-1 sm:grid-cols-6 gap-5">
            <div class="col-span-5 sm:col-span-4 mb-5 sm:mb-0">
                <p class="text-white text-3xl font-bold pt-5">Lorem, ipsum dolor sit amet consectetur adipisicing elit.
                    Culpa, ut
                    laborum tempore dicta eum excepturi sint
                    nemo dolores nulla nesciunt.</p>
                <p class="text-white mb-10 mt-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Sapiente, quam.
                </p>
                <a href="#" class="bg-primary px-5 py-3 rounded-4xl text-white">Learn More</a>
            </div>
            <div class="col-span-5 sm:col-span-2">
                <div class="border p-10 rounded-3xl border-secondary">
                    <div class="pb-5 border-b border-secondary">
                        <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, amet.</p>
                        <p class="text-white">Lorem ipsum dolor sit amet.</p>
                    </div>
                    <div class="grid grid-cols-2 mt-5">
                        <div>
                            <p class="text-white text-4xl">7+</p>
                            <p class="text-white">Country</p>
                        </div>
                        <div>
                            <p class="text-white text-4xl">10+</p>
                            <p class="text-white">Farm</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container m-auto px-5 relative">
        <h2 class="text-white font-bold alfa-slab-one text-5xl pt-20 pb-5">Origins</h2>

        <!-- Element Map & Controls -->
        <div class="relative">
            <div id="map"></div>

            <!-- Tombol Reset Position -->
            <button id="btn-reset-map" class="reset-view-btn" title="Kembali ke posisi awal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                    <path d="M3 3v5h5" />
                </svg>
                Reset Position
            </button>

            <div id="popup" class="ol-popup">
                <a href="#" id="popup-closer" class="ol-popup-closer">&times;</a>
                <div id="popup-content"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Motion --}}
    <script type="module">
        const {
            animate,
            inView,
            delay
        } = Motion

        inView("#speciality", (element) => {
            animate(
                element, {
                    opacity: [0, 1],
                    y: [100, 0],
                    scale: 1.1
                }, {
                    duration: 0.5,
                    easing: "ease-in-out"
                }
            )
            return () => animate(element, {
                opacity: 0,
            })
        })

        delay(() => {
            inView("#hero-text", (element) => {
                animate(
                    element, {
                        opacity: [0.5, 1],
                        scale: 1.1
                    }, {
                        duration: 0.5,
                        easing: "ease-in-out"
                    }
                )
                return () => animate(element, {
                    opacity: 0,
                    scale: 1
                })
            })
        }, 0.5)
    </script>

    {{-- Maps --}}
    <script>
        // Data Center & Zoom Awal Global
        const initialCenter = [70.0, 10.0];
        const initialZoom = 2.3;

        // 1. Layer Peta Utama
        const openfreemap = new ol.layer.Group();

        // 2. Data Koordinat & Detail Lokasi Negara (ditambahkan kode iso 2-letter untuk icon bendera)
        const locations = [{
                name: 'Japan',
                code: 'jp',
                coords: [138.2529, 36.2048],
                description: 'Key export market for high-grade specialty and enzymatic civet coffee.'
            },
            {
                name: 'China',
                code: 'cn',
                coords: [104.1954, 35.8617],
                description: 'Rapidly growing market for luxury and specialty coffee products.'
            },
            {
                name: 'Bangladesh',
                code: 'bd',
                coords: [90.3563, 23.6850],
                description: 'Emerging market for premium imported Indonesian coffee beans.'
            },
            {
                name: 'Malaysia',
                code: 'my',
                coords: [101.9758, 4.2105],
                description: 'Established Southeast Asian hub for specialty coffee distribution.'
            },
            {
                name: 'Australia',
                code: 'au',
                coords: [133.7751, -25.2744],
                description: 'World-renowned specialty coffee market appreciating unique processed profiles.'
            },
            {
                name: 'Peru',
                code: 'pe',
                coords: [-75.0152, -9.1900],
                description: 'South American partner and specialty coffee hub.'
            },
            {
                name: 'Saudi Arabia',
                code: 'sa',
                coords: [45.0792, 23.8859],
                description: 'Major Middle Eastern destination for high-end specialty civet coffee.'
            }
        ];

        // 3. Buat Feature (Titik Marker) dengan Style Ikon Bendera
        const features = locations.map(loc => {
            const feature = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat(loc.coords)),
                name: loc.name,
                description: loc.description
            });

            // Set Style Icon Bendera untuk masing-masing negara
            feature.setStyle(new ol.style.Style({
                image: new ol.style.Icon({
                    anchor: [0.5, 0.5],
                    src: `https://flagcdn.com/w40/${loc.code}.png`,
                    scale: 0.85
                })
            }));

            return feature;
        });

        // 4. Layer Vector Marker
        const coffeeLayer = new ol.layer.Vector({
            source: new ol.source.Vector({
                features: features
            })
        });

        // 5. Kontrol
        const controls = typeof ol.control.defaults === 'function' ?
            ol.control.defaults({
                zoom: false
            }) :
            ol.control.defaults.defaults({
                zoom: false
            });

        // 6. Setup Overlay Popup
        const container = document.getElementById('popup');
        const content = document.getElementById('popup-content');
        const closer = document.getElementById('popup-closer');

        const overlay = new ol.Overlay({
            element: container,
            autoPan: {
                animation: {
                    duration: 250,
                },
            },
        });

        closer.onclick = function() {
            overlay.setPosition(undefined);
            closer.blur();
            return false;
        };

        // 7. Inisialisasi View & Map
        const view = new ol.View({
            center: ol.proj.fromLonLat(initialCenter),
            zoom: initialZoom,
            minZoom: 1.5
        });

        const map = new ol.Map({
            controls: controls,
            layers: [openfreemap, coffeeLayer],
            overlays: [overlay],
            view: view,
            target: 'map',
        });

        // 8. Terapkan Basemap OpenFreeMap
        olms.apply(openfreemap,
            'https://api.maptiler.com/maps/019fdbac-7506-7e6a-980f-560feef941a8/style.json?key=F7vo4PTXSgkVkQxxM3gY'
        );

        // 9. Fitur Klik Marker (Menampilkan Detail)
        map.on('singleclick', function(evt) {
            const feature = map.forEachFeatureAtPixel(evt.pixel, function(feat) {
                return feat;
            });

            if (feature) {
                const coordinates = feature.getGeometry().getCoordinates();
                const name = feature.get('name');
                const description = feature.get('description');

                content.innerHTML = `
                    <h3 class="font-bold text-lg text-primary mb-1">${name}</h3>
                    <p class="text-sm text-gray-300 leading-snug">${description}</p>
                `;
                overlay.setPosition(coordinates);
            } else {
                overlay.setPosition(undefined);
            }
        });

        // 10. Ubah Cursor saat Hover Marker
        map.on('pointermove', function(e) {
            const pixel = map.getEventPixel(e.originalEvent);
            const hit = map.hasFeatureAtPixel(pixel);
            map.getTargetElement().style.cursor = hit ? 'pointer' : '';
        });

        // 11. Handler Tombol Reset Position
        document.getElementById('btn-reset-map').addEventListener('click', function() {
            overlay.setPosition(undefined);

            view.animate({
                center: ol.proj.fromLonLat(initialCenter),
                zoom: initialZoom,
                duration: 600
            });
        });
    </script>
@endpush
