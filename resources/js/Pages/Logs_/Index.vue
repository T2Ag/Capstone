<template>
   <Layout>
    
      <div class="bg-white min-h-[100rem] h-full"> 
          
        <div class="modal fade" id="alert" tabindex="-1" aria-labelledby="alertTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light shadow-lg rounded-lg">
              <div class="modal-body text-center py-5">
                <div class="mb-4">
                  
                  {{ header }}
                
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <p>

                    {{ decodedValue }}
                    {{ client && client.first_name ? client.first_name : 'No Client Data' }}
                    
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>


         <p>{{ error }}</p>
         
         <div class="pt-[15rem]">
            <div class="camera-container mx-auto items-center" v-if="!isScanningPaused">
              <qrcode-stream @init="onInit" @decode="onDecode" class="border border-b-2"></qrcode-stream>
          </div>
         </div>


      </div>

      <form @submit.prevent="submit">
        <input type="hidden" name="client_id" v-model="form.client_id">
      </form>

   </Layout>

</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {QrcodeStream} from 'vue3-qrcode-reader';
import Layout from '@/Layouts/Layout.vue';

const props = defineProps({
   logs: Array,
   client: Object,
   log: Object
})

const error = ref('');
const header = ref('');
const decodedValue = ref('');
const isScanningPaused = ref(false);

const form = useForm({
  client_id: ''
});

async function onInit(promise) {
  error.value = '';

  try {
    const { capabilities } = await promise;

    // If successfully initialized
  } catch (err) {
    if (err.name === 'NotAllowedError') {
      error.value = 'User denied camera access permission';
    } else if (err.name === 'NotFoundError') {
      error.value = 'No suitable camera device installed';
    } else if (err.name === 'NotSupportedError') {
      error.value = 'Page is not served over HTTPS (or localhost)';
    } else if (err.name === 'NotReadableError') {
      error.value = 'Camera may be already in use';
    } else if (err.name === 'OverconstrainedError') {
      error.value = 'Requested front camera is unavailable';
    } else if (err.name === 'StreamApiNotSupportedError') {
      error.value = 'Browser lacks required features';
    } else {
      error.value = 'An unknown error occurred';
    }
  } finally {
    // Hide loading indicator (if any)
  }
}

function onDecode(result) {
  // If no result is found, return early
  if (!result) return;

  // Assign the decoded value to the client_id field in the form
  form.client_id = result;

  // Post the data to the logs store route
  form.post(route('logs.store'), {
    onSuccess: (response) => {
      // Safeguard against null references for log or transaction_id
      if (response.props.log && response.props.log.transaction_id === null) {
        header.value = 'Please Pay At the Cashier';
      } else if (response.props.log) {
        header.value = 'Payment Successful, Please Proceed';
      } else {
        header.value = 'Log data is missing';
      }

      decodedValue.value = result;
      showAlertModal();
    },
    onError: () => {
      header.value = 'Error';
      decodedValue.value = 'Client Doesn\'t Exist';
      showAlertModal();
    }
  });
}

function showAlertModal() {
  isScanningPaused.value = true;

  const alertModal = new bootstrap.Modal(document.getElementById('alert'));
  alertModal.show();

  setTimeout(() => {
    alertModal.hide();
    error.value = '';
    isScanningPaused.value = false;
  }, 4000);
}

</script>

<style>
.camera-container {
  width: 300px; /* Adjust width as needed */
  height: 300px; /* Adjust height as needed */
  position: relative;
}

.camera-container qrcode-stream {
  width: 100%;
  height: 100%;
}
</style>