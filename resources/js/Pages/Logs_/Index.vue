<template>
   <Layout>
    
      <div class="bg-white min-h-[100rem] h-full"> 
          
        <div class="modal fade" id="alert" tabindex="-1" aria-labelledby="alertTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light shadow-lg rounded-lg">
              <div class="modal-body text-center py-5">
                <div class="mb-4">
                  
                  <p class="text-2xl font-extrabold">
                    <template v-if="showSuccess && clientData">
                      <p class="text-success">Success</p>
                    </template>
                    <template v-else>
                      <p class="text-danger">Error</p>
                    </template>
                  </p>
                  <p class="text-lg font-semibold text-gray-700">
                    <template v-if="showSuccess && clientData">
                      <p>Welcome</p>
                    </template>
                    <template v-else>
                      <p>Oops!</p>
                    </template>
                  </p>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-md">
                  <p class="text-lg font-semibold text-gray-900">
                    <template v-if="showSuccess && clientData">
                      {{ clientData.first_name }} {{ clientData.last_name }}
                    </template>
                    <template v-else>
                      {{ error || 'Client doesn\'t exist' }}
                    </template>
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

const error = ref('');
const decodedString = ref('');
const showSuccess = ref(false);
const isScanningPaused = ref(false);
const clientData = ref('');

const props = defineProps({
   logs: Array
})

const form = useForm({
  client_id: ''
});

async function onInit(promise) {
   error.value = '';

  // show loading indicator

  try {
    const { capabilities } = await promise;

    // successfully initialized
  } catch (error) {
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
    // hide loading indicator
  }
};

function onDecode(result) {
  decodedString.value = result;
  form.client_id = result;

  // Submit the form after a successful scan
  form.post(route('logs.store'), {
    onSuccess: (response) => {
      error.value = '';
      showSuccess.value = true;
      isScanningPaused.value = true;

      clientData.value = response.props.client;

      const alertModal = new bootstrap.Modal(document.getElementById('alert'));
      alertModal.show();

      setTimeout(() => {
        alertModal.hide();
        isScanningPaused.value = false; 
        decodedString.value = ''
      }, 4000);
    },
    onError: () => {
      error.value = errors.client_id || 'Client not found or other error occurred';
      showSuccess.value = false;
      isScanningPaused.value = true;

      clientData.value = null;

      const alertModal = new bootstrap.Modal(document.getElementById('alert'));
      alertModal.show();

      setTimeout(() => {
        alertModal.hide();
        isScanningPaused.value = false;
        decodedString.value = ''
      }, 4000);
    }
  });
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