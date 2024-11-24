<template>
   <div class="modal fade" :id="`editAnnouncementModal-${announcement.id}`" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
       <div class="modal-content">
         <div class="modal-header flex justify-between">
           <h5 class="modal-title" id="editModalTitle">Edit Announcement</h5>
           <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span>
           </button>
         </div>
         <form @submit.prevent="submit">
           <div class="modal-body">
             <div class="mb-4">
               <label :for="`title-${announcement.id}`" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
               <input 
                 type="text" 
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                 :id="`title-${announcement.id}`" 
                 v-model="form.title"
                 required
               >
               <span class="text-red-500">{{ form.errors.title }}</span>
             </div>
 
             <div class="mb-4">
               <label :for="`content-${announcement.id}`" class="block text-gray-700 text-sm font-bold mb-2">Content</label>
               <textarea 
                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                 :id="`content-${announcement.id}`" 
                 v-model="form.content"
                 rows="4"
                 required
               ></textarea>
               <span class="text-red-500">{{ form.errors.content }}</span>
             </div>
           </div>
 
           <div class="modal-footer">
             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
             <button type="submit" class="btn btn-primary">Update Announcement</button>
           </div>
         </form>
       </div>
     </div>
   </div>
 </template>
 
 <script setup>
 import { useForm } from '@inertiajs/vue3';
 import { onMounted } from 'vue';
 
 const props = defineProps({
   announcement: {
     type: Object,
     required: true
   }
 });
 
 const form = useForm({
   title: props.announcement.title,
   content: props.announcement.content,
 });
 
 const resetForm = () => {
   form.title = props.announcement.title;
   form.content = props.announcement.content;
 };
 
 onMounted(() => {
   const modalElement = document.querySelector(`#editAnnouncementModal-${props.announcement.id}`);
   if (modalElement) {
     modalElement.addEventListener('show.bs.modal', resetForm);
   }
 });
 
 const submit = () => {
   form.put(route('announcements.update', props.announcement.id), {
     onSuccess: () => {
       resetForm();
       const modalElement = document.querySelector(`#editAnnouncementModal-${props.announcement.id}`);
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
     },
     preserveScroll: true
   });
 };
 </script>