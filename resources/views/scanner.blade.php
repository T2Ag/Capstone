<div class="w-50">
   <div class="bg-white p-2 rounded shadow-sm">
      <h1 class="text-center create mb-5">Scan QR Here</h1>
      <div class="d-flex justify-content-center mb-2">
            <div id='reader' class="border rounded p-3 shadow-sm" style="width: 320px;"></div>
      </div>
      <div class="text-center flex flex-col justify-center">
            <label for="result" class="form-label fw-bold ">ID Number:</label>
            <input type="text" name='result' id='result' class="w-50 mx-auto text-center text-2xl border-0" readonly>
            
            @if(session('info'))
                <div class="alert alert-info mt-3">
                    {{ session('info')['success'] }}
                </div>
            @endif
      </div>
   </div>
</div>

<form action="{{ route('logs.store') }}" method="POST" id="scanForm" style="display: none;">
   @csrf
   <input type="hidden" name="client_id" id="client_id">
</form>

<script src='https://unpkg.com/html5-qrcode' type='text/javascript'></script>
<script>
   let html5QrcodeScanner;

   function onScanSuccess(decodedText, decodedResult) {
      // Validate that the scanned text is a valid client ID
      fetch(`/validate-client/${decodedText}`)
         .then(response => response.json())
         .then(data => {
            if (data.valid) {
               document.getElementById('result').value = decodedText;
               document.getElementById('client_id').value = decodedText;
               html5QrcodeScanner.clear(); // Stop scanning
               document.getElementById('scanForm').submit(); // Submit form
            } else {
               alert('Invalid client ID');
               // Optionally restart the scanner
               html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            }
         })
         .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while validating the client ID');
         });
   }

   function onScanFailure(error) {
      // Optionally log the error or show a user-friendly message
      console.warn(`Code scan error = ${error}`);
   }

   document.addEventListener('DOMContentLoaded', (event) => {
      html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 60, qrbox: 250 },
            false
      );
      html5QrcodeScanner.render(onScanSuccess, onScanFailure);
   });
</script>