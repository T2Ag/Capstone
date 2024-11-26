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
      <div 
        v-if="scanResult" 
        :class="[
          'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4',
          isClosing ? 'animate-fade-out' : 'animate-fade-in'
        ]"
      >
        <div 
          :class="[
            'bg-white rounded-2xl shadow-2xl max-w-xl w-full p-8 text-center',
            isClosing ? 'animate-pop-out' : 'animate-pop-in'
          ]"
        >
          <div class="text-2xl font-bold mb-6 text-gray-800">
            {{ header }}
          </div>
          <div class="bg-gray-100 p-6 rounded-xl">
            <p class="text-gray-800 text-xl">
              {{ client ? 
                `${client.first_name} ${client.middle_initial || ''} ${client.last_name}`.trim() 
                : 'Client Not Found' 
              }}
            </p>
          </div>
        </div>
      </div>

      <!-- Camera Container -->
      <div class="lg:pt-[10rem] pt-[8rem] flex justify-center">
        <div id="reader-container" class="w-full max-w-md">
          <div id="reader" class="w-full h-[337px] border border-gray-300 rounded-lg"></div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
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
const scanResult = ref(null)
let html5QrCode = null

// Inertia form
const form = useForm({
  client_id: ''
})

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
  // Ensure previous scanner is stopped
  if (html5QrCode) {
    try {
      await html5QrCode.stop()
    } catch (stopError) {
      console.warn('Error stopping previous scanner:', stopError)
    }
  }

  // Ensure reader container exists
  const readerContainer = document.getElementById('reader')
  if (!readerContainer) {
    error.value = 'Scanner container not found'
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
  } catch (err) {
    error.value = handleCameraError(err)
    console.error('Scanner initialization error:', err)
  }
}

const isClosing = ref(false)

const onScanSuccess = (decodedText, decodedResult) => {
  html5QrCode.pause(true)
  
  form.client_id = decodedText
  
  form.post(route('logs.store'), {
    onSuccess: (response) => {
      // Set header based on transaction status
      header.value = response.props.log?.transaction_id === null 
        ? 'Payment Required, Please Pay At the Cashier' 
        : 'Payment Successful, Please Proceed'
      
      scanResult.value = true

      // Auto-close after 2 seconds
      setTimeout(() => {
        isClosing.value = true
      }, 4700)

      // Remove popup completely after animation
      setTimeout(() => {
        scanResult.value = null
        isClosing.value = false
        initializeScanner()
      }, 5000)
    },
    onError: () => {
      header.value = 'Error'
      scanResult.value = true

      // Auto-close after 2 seconds
      setTimeout(() => {
        isClosing.value = true
      }, 4700)

      // Remove popup completely after animation
      setTimeout(() => {
        scanResult.value = null
        isClosing.value = false
        initializeScanner()
      }, 5000)
    }
  })
}

// Lifecycle hooks
onMounted(() => {
  initializeScanner()
})

// Cleanup on component unmount
onBeforeUnmount(() => {
  // Stop scanner
  if (html5QrCode) {
    html5QrCode.stop().catch(err => {
      console.error("Error stopping QR scanner:", err)
    })
  }
})
</script>

<style scoped>
.bg-container {
  background: url('gym.jpeg') no-repeat center center/cover;
  background-attachment: fixed;
}

#reader {
  max-width: 100x;
  background-color: rgba(255,255,255,0.1);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes fadeOut {
  from { opacity: 1; }
  to { opacity: 0; }
}

@keyframes popIn {
  0% { 
    opacity: 0; 
    transform: scale(0.7); 
  }
  70% { 
    opacity: 0.7; 
    transform: scale(1.03); 
  }
  100% { 
    opacity: 1; 
    transform: scale(1); 
  }
}

@keyframes popOut {
  0% { 
    opacity: 1; 
    transform: scale(1); 
  }
  30% { 
    opacity: 0.7; 
    transform: scale(1.03); 
  }
  100% { 
    opacity: 0; 
    transform: scale(0.7); 
  }
}

.animate-fade-in {
  animation: fadeIn 0.3s ease-out;
}

.animate-fade-out {
  animation: fadeOut 0.3s ease-in;
}

.animate-pop-in {
  animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.animate-pop-out {
  animation: popOut 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}
</style>