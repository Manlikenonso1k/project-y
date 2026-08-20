@extends('layout')

@section('title', 'Pay with Gift Card')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('order.success', $order) }}">Order Success</a></li>
    <li class="breadcrumb-item active">Gift Card Payment</li>
@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    .gift-card-page { --gift-green: #00703c; --gift-bg: #fcf8fb; --gift-field: #f5f5f7; --gift-line: #e5e5e5; font-family: Inter, sans-serif; background: var(--gift-bg); color: #1b1b1d; min-height: 680px; }
    .gift-card-shell { max-width: 480px; margin: 0 auto; min-height: 680px; padding: 20px 20px 116px; }
    .gift-card-header { display: grid; grid-template-columns: 40px 1fr 40px; align-items: center; min-height: 48px; margin-bottom: 28px; }
    .gift-card-back { color: #1b1b1d; font-size: 28px; line-height: 1; text-decoration: none; }
    .gift-card-header h1 { font-size: 22px; font-weight: 700; text-align: center; margin: 0; }
    .gift-card-preview { background: linear-gradient(135deg, #0066cc, #67a7eb); border-radius: 16px; box-shadow: 0 4px 16px rgba(0, 55, 110, .14); color: white; min-height: 158px; padding: 20px; display: flex; flex-direction: column; justify-content: space-between; margin: 0 auto 28px; max-width: 256px; }
    .gift-card-preview .apple { font-size: 50px; line-height: 1; text-align: center; }
    .gift-card-preview small { font-weight: 600; opacity: .9; }
    .gift-card-field { background: var(--gift-field); border: 1px solid var(--gift-line); border-radius: 10px; min-height: 58px; padding: 10px 14px; margin-bottom: 14px; }
    .gift-card-field label { display: block; color: #3f4940; font-size: 12px; font-weight: 600; margin-bottom: 4px; }
    .gift-card-field select, .gift-card-field input { border: 0; background: transparent; box-shadow: none !important; outline: 0; padding: 0; width: 100%; color: #1b1b1d; font-size: 16px; }
    .gift-card-value { text-align: center; padding: 30px 0 12px; }
    .gift-card-value small { color: #3f4940; font-size: 14px; }
    .gift-card-value strong { display: block; font-size: 34px; line-height: 1.2; letter-spacing: -.03em; margin: 6px 0; }
    .gift-card-value p { color: #6f7a6f; font-size: 13px; margin: 0; }
    .gift-card-note { border-radius: 10px; background: #edf7f0; color: #245237; font-size: 13px; line-height: 1.5; padding: 13px; margin-top: 18px; }
    .gift-card-action { position: fixed; z-index: 30; bottom: 0; left: 0; right: 0; padding: 16px 20px; background: rgba(252, 248, 251, .94); border-top: 1px solid var(--gift-line); backdrop-filter: blur(10px); }
    .gift-card-action-inner { max-width: 480px; margin: auto; }
    .gift-card-primary, .gift-card-secondary { border-radius: 12px; min-height: 56px; width: 100%; font-size: 18px; font-weight: 700; }
    .gift-card-primary { color: white; background: var(--gift-green); border: 1px solid var(--gift-green); }
    .gift-card-secondary { color: var(--gift-green); background: white; border: 1.5px solid var(--gift-green); }
    .gift-card-modal { position: fixed; inset: 0; z-index: 40; background: rgba(27, 27, 29, .48); display: flex; align-items: flex-end; }
    .gift-card-modal[hidden] { display: none; }
    .gift-card-sheet { width: 100%; max-width: 480px; margin: auto; background: white; border-radius: 24px 24px 0 0; padding: 12px 20px 24px; box-shadow: 0 -4px 20px rgba(0,0,0,.15); }
    .gift-card-handle { width: 48px; height: 5px; background: #c6c6c8; border-radius: 99px; margin: 0 auto 20px; }
    .gift-card-sheet-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
    .gift-card-sheet-header h2 { font-size: 20px; font-weight: 600; margin: 0; }
    .gift-card-close { border: 0; background: #f0edef; border-radius: 50%; width: 38px; height: 38px; font-size: 25px; color: #3f4940; }
    .gift-card-upload-zone { min-height: 138px; border: 2px dashed #becabd; border-radius: 14px; display: flex; gap: 12px; align-items: center; overflow-x: auto; padding: 14px; margin-bottom: 12px; }
    .gift-card-add-image { width: 88px; height: 88px; flex: 0 0 auto; border: 1px solid #becabd; background: #fff; color: #1b1b1d; border-radius: 12px; display: flex; flex-direction: column; justify-content: center; align-items: center; font-size: 13px; }
    .gift-card-thumb { position: relative; width: 88px; height: 88px; flex: 0 0 auto; }
    .gift-card-thumb img { width: 100%; height: 100%; border-radius: 10px; object-fit: cover; border: 1px solid var(--gift-line); }
    .gift-card-thumb button { position: absolute; right: -7px; top: -7px; border: 0; background: #1b1b1d; color: white; width: 24px; height: 24px; border-radius: 50%; }
    .gift-card-sheet-help { color: #6f7a6f; font-size: 14px; margin-bottom: 18px; }
    @media (min-width: 992px) { .gift-card-page { border: 1px solid var(--gift-line); border-radius: 16px; max-width: 520px; margin: 40px auto; overflow: hidden; } .gift-card-action { position: absolute; } }
</style>
@endpush

@section('content')
<div class="gift-card-page">
    <form method="POST" action="{{ route('gift-card-payment.submit', $order) }}" enctype="multipart/form-data" id="gift-card-form">
        @csrf
        <div class="gift-card-shell">
            <header class="gift-card-header">
                <a class="gift-card-back" href="{{ route('order.success', $order) }}" aria-label="Back">‹</a>
                <h1>Pay with Gift Card</h1>
                <span></span>
            </header>

            <section class="gift-card-preview" aria-label="Gift card preview">
                <small>Gift Card Payment</small>
                <div class="apple"></div>
                <small id="card-preview-label">Apple Gift Card</small>
            </section>

            @if ($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="gift-card-field">
                <label for="card_type">GIFT CARD TYPE</label>
                <select id="card_type" name="card_type">
                    @foreach (['Apple Gift Card', 'Amazon Gift Card', 'Google Play Gift Card', 'Steam Gift Card'] as $cardType)
                        <option value="{{ $cardType }}" @selected(old('card_type', 'Apple Gift Card') === $cardType)>{{ $cardType }} (USD)</option>
                    @endforeach
                </select>
            </div>

            <div class="gift-card-field">
                <label for="card_amount">CARD FACE VALUE (USD)</label>
                <input id="card_amount" name="card_amount" type="number" inputmode="decimal" min="1" max="10000" step="0.01" placeholder="Enter card value" value="{{ old('card_amount') }}" required>
            </div>

            <section class="gift-card-value">
                <small>ORDER TOTAL</small>
                <strong>${{ number_format($order->total, 2) }}</strong>
                <p>Upload clear photos of the front and back of your gift card.</p>
            </section>

            <div class="gift-card-note">Gift cards are reviewed manually. Your order remains pending until we verify the card and its balance.</div>
        </div>

        <div class="gift-card-action">
            <div class="gift-card-action-inner"><button class="gift-card-primary" type="button" id="open-upload">Upload Photos &#128247;</button></div>
        </div>

        <div class="gift-card-modal" id="upload-modal" hidden aria-modal="true" role="dialog" aria-labelledby="upload-title">
            <div class="gift-card-sheet">
                <div class="gift-card-handle"></div>
                <div class="gift-card-sheet-header"><span></span><h2 id="upload-title">Upload card images</h2><button class="gift-card-close" type="button" id="close-upload" aria-label="Close">×</button></div>
                <div class="gift-card-upload-zone" id="preview-zone">
                    <button class="gift-card-add-image" type="button" id="choose-images"><span style="font-size:28px">&#128247;</span>Upload Photo</button>
                </div>
                <input id="gift-card-images" name="images[]" type="file" accept="image/jpeg,image/png,image/webp" multiple hidden>
                <p class="gift-card-sheet-help"><span id="image-count">0</span> of 10 images selected</p>
                <button class="gift-card-primary" type="submit">Confirm Gift Card Payment</button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('upload-modal');
    var input = document.getElementById('gift-card-images');
    var zone = document.getElementById('preview-zone');
    var addButton = document.getElementById('choose-images');
    var imageCount = document.getElementById('image-count');
    var files = [];
    document.getElementById('open-upload').addEventListener('click', function () { modal.hidden = false; });
    document.getElementById('close-upload').addEventListener('click', function () { modal.hidden = true; });
    addButton.addEventListener('click', function () { input.click(); });
    input.addEventListener('change', function () {
        Array.prototype.forEach.call(input.files, function (file) { if (files.length < 10) files.push(file); });
        renderFiles();
    });
    function renderFiles() {
        zone.querySelectorAll('.gift-card-thumb').forEach(function (node) { node.remove(); });
        files.forEach(function (file, index) {
            var reader = new FileReader();
            reader.onload = function (event) {
                var thumb = document.createElement('div'); thumb.className = 'gift-card-thumb';
                thumb.innerHTML = '<img alt="Selected gift card image"><button type="button" aria-label="Remove image">×</button>';
                thumb.querySelector('img').src = event.target.result;
                thumb.querySelector('button').addEventListener('click', function () { files.splice(index, 1); renderFiles(); });
                zone.insertBefore(thumb, addButton);
            }; reader.readAsDataURL(file);
        });
        imageCount.textContent = files.length;
        addButton.hidden = files.length >= 10;
    }
    document.getElementById('gift-card-form').addEventListener('submit', function (event) {
        if (!files.length) { event.preventDefault(); alert('Please upload at least one gift card image.'); return; }
        var transfer = new DataTransfer(); files.forEach(function (file) { transfer.items.add(file); }); input.files = transfer.files;
    });
    document.getElementById('card_type').addEventListener('change', function () { document.getElementById('card-preview-label').textContent = this.value; });
});
</script>
@endpush
