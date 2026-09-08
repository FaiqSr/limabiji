{{-- resources/views/landingpages/store/partials/payment-polling.blade.php --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const pollUrl = '{{ $order->status_token ? route('store.order.payment-status', [$order->order_number, 'token' => $order->status_token]) : route('store.order.payment-status', $order->order_number) }}';
    const reloadMs = 6000;

    // Copy-to-clipboard buttons
    document.querySelectorAll('[data-copy]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const target = document.querySelector(btn.getAttribute('data-copy'));
            if (!target) return;
            const text = target.textContent.trim();
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => flashCopied(btn));
            } else {
                const ta = document.createElement('textarea');
                ta.value = text;
                document.body.appendChild(ta);
                ta.select();
                document.execCommand('copy');
                document.body.removeChild(ta);
                flashCopied(btn);
            }
        });
    });

    function flashCopied(btn) {
        const original = btn.textContent;
        btn.textContent = '{{ __('store.pay_copied') }}';
        setTimeout(() => { btn.textContent = original; }, 1500);
    }

    // Scroll to instructions when the pay button is pressed
    const payNow = document.getElementById('btn-pay-now');
    if (payNow) {
        payNow.addEventListener('click', () => {
            const panel = document.querySelector('[data-payment-instructions]');
            if (panel) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    }

    // Expiry countdown
    const expiryEl = document.getElementById('payment-expiry');
    if (expiryEl) {
        const expiryTs = parseInt(expiryEl.getAttribute('data-expires'), 10);
        const tick = () => {
            const diff = expiryTs - Math.floor(Date.now() / 1000);
            if (diff <= 0) {
                expiryEl.textContent = '00:00:00';
                window.location.reload();
                return;
            }
            const h = String(Math.floor(diff / 3600)).padStart(2, '0');
            const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
            const s = String(diff % 60).padStart(2, '0');
            expiryEl.textContent = h + ':' + m + ':' + s;
        };
        tick();
        setInterval(tick, 1000);
    }

    // Status polling: reload the page once the order is no longer pending
    const paidCheck = document.getElementById('btn-paid-check');
    if (paidCheck) {
        paidCheck.addEventListener('click', pollStatus);
    }

    function pollStatus() {
        fetch(pollUrl, { headers: { 'Accept': 'application/json' } })
            .then((res) => res.json())
            .then((data) => {
                if (data.payment_status !== 'pending') {
                    window.location.reload();
                } else if (data.payment_status === 'pending') {
                    if (paidCheck) {
                        const original = paidCheck.textContent;
                        paidCheck.textContent = '{{ __('store.pay_checking') }}';
                        setTimeout(() => { paidCheck.textContent = original; }, 1500);
                    }
                }
            })
            .catch(() => { /* transient network error; retry next tick */ });
    }

    setInterval(pollStatus, reloadMs);
});
</script>
