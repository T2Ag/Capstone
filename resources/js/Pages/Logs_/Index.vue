<template>
   <Layout>
    
      <div class="bg-white min-h-[100rem] h-full"> 
          
          <div class="modal fade" id="alert" tabindex="-1" aria-labelledby="alertTitle" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="alertTitle">Alert</h5>
                    </div>
                    <div class="modal-body">
                      Success
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
    onSuccess: () => {
      error.value = '';
      showSuccess.value = true;
      isScanningPaused.value = true;

      const alertModal = new bootstrap.Modal(document.getElementById('alert'));
      alertModal.show();

      setTimeout(() => {
        alertModal.hide();
        showSuccess.value = false;
        isScanningPaused.value = false;
        decodedString.value = ''
      }, 2000);

    },
    onError: () => {
      error.value = 'Failed to log. Client does not exist or other error.';
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