<template>
   <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="createModalTitle">Add Client</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
            
           <div class="modal-body">

               <div class="col-12 mb-3">
                  Please select a client to be added in the "{{ training.name }}" by {{ training.coach.first_name }} {{ training.coach.middle_initial }}. {{ training.coach.last_name }}.
               </div>
               
               <div class="col-12 mb-3">
                  <label for="clients" class="form-label">Client Name</label>
                  <input list="clients_options" class="form-control" id="clients" name="clients" autocomplete="off" type="text" v-model="selectedClientName" @input="updateClientId" placeholder="Search and select a Client">
                  <datalist id="clients_options">
                     <option v-for="client in clients">
                        {{ client.first_name }} {{ client.middle_initial }} {{ client.last_name }}
                     </option>
                  </datalist>
                  <input type="hidden" name="client_id" v-model="form.client_id"/>
                  <span class="text-red-500">{{ form.errors.client_id }}</span>
               </div>

               <div class="flex justify-between text-[30px] font-bold">
                  <div>
                     Total:
                  </div>
                  <div>
                     {{ training.price }}
                  </div>
               </div>

           </div>

           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
             <button type="submit" class="btn btn-primary">Add Client</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
  clients: Array,
  training: Object
 });

const form = useForm({
   client_id: '',
   training_id: props.training.id
});

const selectedClientName = ref('');

const updateClientId = () => {

  const client = props.clients.find(
    c => `${c.first_name} ${c.middle_initial} ${c.last_name}` === selectedClientName.value
  );

    if (client) {
        form.client_id = client.id;
    } else {
        form.client_id = '';
    }
};

const resetSelectedClientName = () => {
   selectedClientName.value = ''
};
 
const submit = () => {
  form.put(route('trainings.addClient', props.training.id), {
    onSuccess: () => {
      form.reset();
      
      const modalElement = document.querySelector('#createModal');
      if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
      }

      resetSelectedClientName();
    },
    onError: (errors) => {
      errors.value = errors;
    },
  });
};
 </script>
 