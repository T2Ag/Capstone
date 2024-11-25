<template>
  <Layout>
     <!-- Background Container -->
     <div class="bg-container min-h-screen overflow-hidden "> 
         
       <!-- Alert Modal -->
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
       
       <!-- Camera Container -->
       <div class="lg:pt-[10rem] pt-[8rem]">
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
import { QrcodeStream } from 'vue3-qrcode-reader';
import Layout from '@/Layouts/Layout.vue';


const props = defineProps({
  logs: Array,
  client: Object,
  log: Object
});

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
 } catch (err) {
   error.value = handleCameraError(err);
 }
}

function handleCameraError(err) {
 const errors = {
   NotAllowedError: 'User denied camera access permission',
   NotFoundError: 'No suitable camera device installed',
   NotSupportedError: 'Page is not served over HTTPS (or localhost)',
   NotReadableError: 'Camera may be already in use',
   OverconstrainedError: 'Requested front camera is unavailable',
   StreamApiNotSupportedError: 'Browser lacks required features'
 };
 return errors[err.name] || 'An unknown error occurred';
}

function onDecode(result) {
 if (!result) return;

 form.client_id = result;

 form.post(route('logs.store'), {
   onSuccess: (response) => {
     header.value = response.props.log?.transaction_id === null 
       ? 'Please Pay At the Cashier' 
       : 'Payment Successful, Please Proceed';
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

<style scoped>
/* Background container with fixed image and blur effect */
.bg-container {
  background: url('gym.jpeg') no-repeat center center/cover;
  background-attachment: fixed;

}

/* Camera container styling */
.camera-container {
 position: relative;
 width: 300px;
 height: 300px;
 z-index: 1; /* Ensure the camera is above the blurred background */
}

.camera-container qrcode-stream {
 width: 100%;
 height: 100%;
}

/* Ensure the modal content is above the blurred background */
.modal-content {
 z-index: 2;
}
</style>
