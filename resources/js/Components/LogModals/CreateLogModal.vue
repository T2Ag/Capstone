
<template>
   <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="createModalTitle">Create User</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
           <div class="modal-body">

            <div class="mb-3">
                
               <label for="create-client_id" class="form-label">Client</label>
               <select class="form-control" id="create-client_id" v-model="form.client_id">
                  <option value="">Select a Client</option>
                  <option v-for="client in clients" :key="client.id" :value="client.id">
                  {{ client.first_name }} {{ client.last_name }}
                  </option>
               </select>
               <span class="text-red-500">{{ form.errors.client_id }}</span>
            </div>
             
           </div>
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary">Create Log</button>
           </div>
         </form>
       </div>
     </div>
   </div>

  <!-- Alert Modal -->
  <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content bg-light shadow-lg rounded-lg">
        <div class="modal-body text-center py-5">
          <div class="mb-4">
            {{ header }}
          </div>
          <div class="bg-white p-4 rounded-lg shadow-md">
            <p>
              {{ alertMessage }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  clients: Array
});

const form = useForm({
  client_id: ''
});

const errors = ref({});
const header = ref('');
const alertMessage = ref('');

const submit = () => {
  errors.value = {};
  form.post(route('logs.manualStore'), {
    onError: (errors) => {
      errors.value = errors;
    },
    onSuccess: (response) => {
      form.reset();
      const createModalElement = document.querySelector('#createModal');
      if (createModalElement) {
        const createModal = bootstrap.Modal.getInstance(createModalElement);
        if (createModal) {
          createModal.hide();
        }
      }
      
       // Set header and alert message based on response
      if (response.props.log && response.props.log.transaction_id === null) {
        header.value = 'Please Pay At the Cashier';
        alertMessage.value = `Log created for ${response.props.client.first_name} ${response.props.client.last_name}. Payment required.`;
      } else if (response.props.log) {
        header.value = 'Payment Successful, Please Proceed';
        alertMessage.value = `Log created for ${response.props.client.first_name} ${response.props.client.last_name}. Payment confirmed.`;
      } else {
        header.value = 'Log Data is Missing';
        alertMessage.value = 'An error occurred while creating the log. Please try again.';
      }

      showAlertModal();
    }
  });
};

const showAlertModal = () => {
  const alertModal = new bootstrap.Modal(document.getElementById('alertModal'));
  alertModal.show();

  setTimeout(() => {
    alertModal.hide();
  }, 4000);
};
 </script>
 