<template>
   <div class="modal fade" :id="`editModal-${training.id}`" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="editModalTitle">Edit Training</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
            
           <div class="modal-body">

              <!-- First Name -->
              <div class="col-12 mb-3">
                <label for="name" class="form-label">Training Name</label>
                <input type="text" class="form-control" id="name" name="name" v-model="form.name">
                <span class="text-red-500">{{ form.errors.name }}</span>
              </div>

              <!-- Price -->
              <div class="col-12 mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" v-model="form.price">
                <span class="text-red-500">{{ form.errors.price }}</span>
              </div>

           </div>

           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" >Close</button>
             <button type="submit" class="btn btn-primary">Update Training</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { ref, onMounted } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
  training: Object
 });

const form = useForm({
   coach_id: props.training.coach_id,
   name: props.training.name,
   price: props.training.price
});


const submit = () => {
  form.put(route('trainingList.update',  props.training.id), {
    onSuccess: () => {
      form.reset();
      const modalElement = document.querySelector(`#editModal-${props.training.id}`);
      if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
      }
    },
    onError: (errors) => {
      errors.value = errors;
    },
  });
};
 </script>
 