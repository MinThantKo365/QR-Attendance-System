@extends('layouts.app')

@section('title', 'QR Scanner')

@section('content')
    <div class="container scanner-page">
        <div class="scanner-wrap">
            <h2 class="mb-1">Attendance Scanner</h2>
            <p class="text-muted mb-4">Scan a guest QR code with the camera, or choose an image file.</p>

            <div class="scanner-card">
                <div class="scanner-header">
                    <div class="scanner-header-info">
                        <div class="scanner-header-icon">
                            <i class="bi bi-camera-video"></i>
                        </div>
                        <div>
                            <strong>Camera</strong>
                            <small>Ready when you start a scan</small>
                        </div>
                    </div>
                    <span id="scannerStatus" class="scanner-status">Idle</span>
                </div>

                <div class="scanner-frame">
                    <div id="reader"></div>
                    <div class="scan-corners">
                        <span class="tl"></span>
                        <span class="tr"></span>
                        <span class="bl"></span>
                        <span class="br"></span>
                    </div>
                    <div id="cameraPlaceholder" class="scanner-placeholder">
                        <i class="bi bi-qr-code-scan"></i>
                        <p>Camera is off</p>
                        <small>Click Scan QR Code to open the camera</small>
                    </div>
                </div>

                <div class="scanner-actions">
                    <button type="button" class="btn btn-primary" id="startCameraBtn">
                        <i class="bi bi-qr-code-scan"></i>
                        Scan QR Code
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="scanFileBtn">
                        <i class="bi bi-image"></i>
                        Scan Image File
                    </button>
                    <input type="file" id="qrFileInput" accept="image/*">
                </div>

                <div id="message" class="scanner-message">
                    Waiting for a scan.
                </div>
            </div>
        </div>

        <div id="welcomePopup" class="scanner-welcome-popup d-none">
            <div class="scanner-welcome-card">
                <i class="bi bi-check-circle-fill"></i>
                <h3 id="welcomePopupTitle">Welcome!</h3>
                <p id="welcomePopupText"></p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        const html5QrCode = new Html5Qrcode('reader');
        const messageEl = document.getElementById('message');
        const statusEl = document.getElementById('scannerStatus');
        const placeholderEl = document.getElementById('cameraPlaceholder');
        const startCameraBtn = document.getElementById('startCameraBtn');
        const scanFileBtn = document.getElementById('scanFileBtn');
        const qrFileInput = document.getElementById('qrFileInput');
        const welcomePopup = document.getElementById('welcomePopup');
        const welcomePopupTitle = document.getElementById('welcomePopupTitle');
        const welcomePopupText = document.getElementById('welcomePopupText');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const scanUrl = @json(route('attendance.scan'));

        let cameraRunning = false;
        let lastScanned = '';
        let processing = false;
        let welcomeTimer = null;

        function showMessage(type, text) {
            messageEl.className = 'scanner-message' + (type ? ' is-' + type : '');
            messageEl.textContent = text;
        }

        function setStatus(text, state) {
            statusEl.className = 'scanner-status' + (state ? ' ' + state : '');
            statusEl.textContent = text;
        }

        function setPlaceholder(visible) {
            placeholderEl.classList.toggle('d-none', !visible);
        }

        function showWelcomePopup(name) {
            welcomePopupTitle.textContent = 'Welcome!';
            welcomePopupText.textContent = name;
            welcomePopup.classList.remove('d-none');

            if (welcomeTimer) {
                clearTimeout(welcomeTimer);
            }

            welcomeTimer = setTimeout(() => {
                welcomePopup.classList.add('d-none');
            }, 5000);
        }

        async function recordAttendance(inviteId) {
            const response = await fetch(scanUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ invite_id: inviteId }),
                credentials: 'same-origin',
            });

            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                throw {
                    message: response.status === 401
                        ? 'Please log in as a scanner user before scanning.'
                        : 'Server returned an invalid response. Please refresh and try again.',
                };
            }

            const data = await response.json();

            if (!response.ok) {
                throw data;
            }

            return data;
        }

        async function onScanSuccess(decodedText) {
            if (processing || decodedText === lastScanned) {
                return;
            }

            processing = true;
            lastScanned = decodedText;
            setStatus('Processing', 'is-on');
            showMessage('', 'Checking invitation...');

            try {
                const data = await recordAttendance(decodedText);

                setStatus('Checked in', 'is-ok');
                showMessage('success', data.message);
                showWelcomePopup(data.name);
            } catch (error) {
                setStatus('Error', 'is-error');

                if (error.name) {
                    showMessage('danger', error.message + ' (' + error.name + ')');
                } else {
                    showMessage('danger', error.message || 'Could not record attendance.');
                }
            }

            setTimeout(() => {
                processing = false;
                lastScanned = '';

                if (cameraRunning) {
                    setStatus('Scanning', 'is-on');
                    showMessage('', 'Point the camera at the next QR code.');
                } else {
                    setStatus('Idle');
                }
            }, 5000);
        }

        async function startCamera() {
            if (cameraRunning) {
                return;
            }

            try {
                setPlaceholder(false);
                await html5QrCode.start(
                    { facingMode: 'environment' },
                    {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    onScanSuccess
                );

                cameraRunning = true;
                startCameraBtn.innerHTML = '<i class="bi bi-stop-circle"></i> Stop Camera';
                setStatus('Scanning', 'is-on');
                showMessage('', 'Point the camera at a QR code.');
            } catch (error) {
                cameraRunning = false;
                setPlaceholder(true);
                setStatus('Idle');
                showMessage('danger', 'Could not start the camera. Check permission or scan an image file.');
            }
        }

        async function stopCamera() {
            if (!cameraRunning) {
                return;
            }

            await html5QrCode.stop();
            cameraRunning = false;
            startCameraBtn.innerHTML = '<i class="bi bi-qr-code-scan"></i> Scan QR Code';
            setStatus('Idle');
            setPlaceholder(true);
        }

        startCameraBtn.addEventListener('click', async function () {
            if (cameraRunning) {
                await stopCamera();
                showMessage('', 'Camera stopped.');
                return;
            }

            await startCamera();
        });

        scanFileBtn.addEventListener('click', function () {
            qrFileInput.click();
        });

        qrFileInput.addEventListener('change', async function (event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            try {
                await stopCamera();
                setPlaceholder(false);
                const decodedText = await html5QrCode.scanFile(file, true);
                onScanSuccess(decodedText);
            } catch (error) {
                setPlaceholder(true);
                showMessage('danger', 'No QR code found in this image.');
            }

            event.target.value = '';
        });
    </script>
@endpush
