  <template>
    <Layout>
      <div class="bg-container min-h-screen overflow-hidden relative">
        <!-- Error Display -->
        <div v-if="error" class="absolute top-4 left-0 right-0 z-50 px-4">
          <div class="bg-red-500 text-white p-3 rounded-lg text-center">
            {{ error }}
          </div>
        </div>

        <!-- Scan Result Popup -->
        <div v-if="scanResult" class="absolute inset-0 z-40 flex items-center justify-center bg-black bg-opacity-50 p-4">
          <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 text-center">
            <div class="text-xl font-bold mb-4">
              {{ header }}
            </div>
            <div class="bg-gray-100 p-4 rounded-lg">
              <p class="text-gray-800">
                {{ client ? 
                  `${client.first_name} ${client.middle_initial || ''}. ${client.last_name}`.trim() 
                  : 'Client Not Found' 
                }}
              </p>
            </div>
            <button 
              @click="clearScanResult" 
              class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition"
            >
              Confrim
            </button>
          </div>
        </div>

        <!-- Camera Container -->
        <div class="lg:pt-[10rem] pt-[8rem] flex justify-center">
          <div id="reader-container" class="w-full max-w-md">
            <div id="reader" class="w-full h-[300px] border border-gray-300 rounded-lg"></div>
          </div>
        </div>
      </div>
    </Layout>
  </template>

  <script setup>
  import { ref, onMounted, onUnmounted, nextTick } from 'vue'
  import { Html5Qrcode } from 'html5-qrcode'
  import { useForm } from '@inertiajs/vue3'
  import Layout from '@/Layouts/Layout.vue'

  const props = defineProps({
    logs: Array,
    client: Object,
    log: Object
  })

  // Reactive variables
  const error = ref('')
  const header = ref('')
  const decodedValue = ref('')
  const scanResult = ref(null)
  const isInitializing = ref(false)
  const isScanning = ref(false)
  let html5QrCode = null

  // Inertia form
  const form = useForm({
    client_id: ''
  })

  // Clear scan result and resume scanning
  const clearScanResult = async () => {
    scanResult.value = null
    header.value = ''
    decodedValue.value = ''
    error.value = ''
    
    // Wait for DOM to update
    await nextTick()
    
    // Reinitialize scanner
    initializeScanner()
  }

  // Camera error handler
  function handleCameraError(err) {
    const errors = {
      NotAllowedError: 'User denied camera access permission',
      NotFoundError: 'No suitable camera device installed',
      NotSupportedError: 'Page is not served over HTTPS (or localhost)',
      NotReadableError: 'Camera may be already in use',
      OverconstrainedError: 'Requested front camera is unavailable',
      StreamApiNotSupportedError: 'Browser lacks required features'
    }
    return errors[err.name] || 'An unknown error occurred'
  }

  // Initialize scanner function
  const initializeScanner = async () => {
    // Prevent multiple initializations
    if (isInitializing.value || isScanning.value) return

    isInitializing.value = true

    // Ensure previous scanner is stopped
    await stopScanner()

    // Ensure reader container exists
    const readerContainer = document.getElementById('reader')
    if (!readerContainer) {
      error.value = 'Scanner container not found'
      isInitializing.value = false
      return
    }

    // Create new scanner instance
    try {
      html5QrCode = new Html5Qrcode('reader')
      
      // Scanner configuration
      const config = { 
        fps: 10, 
        qrbox: { 
          width: 250, 
          height: 250 
        } 
      }
      
      // Start scanning
      await html5QrCode.start(
        { facingMode: "environment" }, 
        config, 
        onScanSuccess
      )
      
      isScanning.value = true
    } catch (err) {
      error.value = handleCameraError(err)
      console.error('Scanner initialization error:', err)
    } finally {
      isInitializing.value = false
    }
  }

  // Scan success handler
  const onScanSuccess = (decodedText, decodedResult) => {
    // Pause the scanner
    html5QrCode.pause(true)
    
    // Set client ID
    form.client_id = decodedText
    
    // Submit form
    form.post(route('logs.store'), {
      onSuccess: (response) => {
        // Set header based on transaction status
        header.value = response.props.log?.transaction_id === null 
          ? 'Please Pay At the Cashier' 
          : 'Payment Successful, Please Proceed'
        
        // Set decoded value and scan result
        decodedValue.value = decodedText
        scanResult.value = true
        
        // Update scanning state
        isScanning.value = false
      },
      onError: () => {
        // Handle submission error
        header.value = 'Error'
        decodedValue.value = 'Client Doesn\'t Exist'
        scanResult.value = true
        
        // Update scanning state
        isScanning.value = false
      }
    })
  }

  // Stop scanner with additional safety checks
  const stopScanner = async () => {
    try {
      if (html5QrCode) {
        // Check if the scanner is currently running
        if (isScanning.value) {
          await html5QrCode.stop()
        }
        html5QrCode = null
        isScanning.value = false
      }
    } catch (err) {
      // Log error but don't throw to prevent page navigation issues
      console.error("Error stopping QR scanner:", err)
    }
  }

  // Lifecycle hooks
  onMounted(() => {
    // Add event listener for Inertia page navigation
    document.addEventListener('inertia:before', stopScanner)
    
    // Initialize scanner
    initializeScanner()
  })

  onUnmounted(() => {
    // Remove event listener
    document.removeEventListener('inertia:before', stopScanner)
    
    // Stop scanner
    stopScanner()
  })
  </script>

  <style scoped>
  .bg-container {
    background: url('gym.jpeg') no-repeat center center/cover;
    background-attachment: fixed;
  }

  #reader {
    max-width: 100%;
    height: 337px;
    background-color: rgba(255,255,255,0.1);
  }
  </style>