<template>

<Layout>
   <div class="p-3">
      <div class="pb-4">
         <p class="text-[30px] text-gray-600">ANNOUNCEMENTS</p>
      </div>


      <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mx-3">

         <h2 class="text-[20px] mb-4">Create Announcement</h2>

         <form @submit.prevent="submitAnnouncement" >
            <div class="mb-4">
               <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                  Title
               </label>
               <input 
                  v-model="form.title" 
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                  id="title" 
                  type="text" 
                  placeholder="Announcement Title"
                  required
               >
            </div>
            
            <div class="mb-4">
               <label class="block text-gray-700 text-sm font-bold mb-2" for="content">
                  Content
               </label>
               <textarea 
                  v-model="form.content" 
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                  id="content" 
                  placeholder="Announcement Details"
                  rows="4"
                  required
               ></textarea>
            </div>
            
            <div class="flex items-center justify-between">
               <button 
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                  type="submit"
               >
                  Create Announcement
               </button>
            </div>
         </form>      
      </div>


      <!-- Announcements Section -->
      <div class="bg-white mt-4 m-3 rounded">
         <div class="px-6 py-4 bg-gradient-to-r from-gray-100 to-gray-200 border-b border-gray-300 rounded">
            <p class="text-xl text-gray-700 font-semibold flex items-center">
               <i class="bi bi-calendar-event mr-3 text-gray-600"></i>
               ANNOUNCEMENTS
            </p>
         </div>

         <table class="w-full">
            <thead>
               <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Announcement Details
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                     Date
                  </th>
               </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
               <tr 
                  v-for="announcement in announcements.data" 
                  :key="announcement.id"
                  class="hover:bg-gray-50 transition-colors duration-200"
               >
                  <td class="px-6 py-4">
                     <div class="text-sm font-medium text-gray-900">
                        {{ announcement.title }}
                     </div>
                     <div class="text-xs text-gray-500 mt-1 line-clamp-2">
                        {{ announcement.content }}
                     </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                     <div class="text-sm text-gray-500">
                        {{ formatDate(announcement.created_at) }}
                     </div>
                  </td>
               </tr>
            </tbody>
         </table>

         <div v-if="announcements.length === 0" class="text-center py-6 text-gray-500">
            <i class="bi bi-info-circle mr-2"></i>
            No announcements at this time
         </div>

         <Pagination class="flex mt-4 justify-end" :links="announcements.links" preserve-scroll/>

      </div>

      <EditAnnouncementModal 
         v-for="announcement in announcements.data"
         :key="`modal-${announcement.id}`"
         :announcement="announcement"
         preserve-scroll
      />

      <!-- Delete Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalTitle">Delete Announcement</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Are you sure you want to delete announcement "{{ deleteForm.title }}"?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="deleteAnnouncement">Delete</button>
               </div>
            </div>
         </div>
      </div>

   </div>

</Layout>

</template>

<script setup>

import { useForm } from '@inertiajs/vue3';
import Layout from '../../Layouts/Layout.vue';
import Pagination from '../../Components/Pagination.vue';
import EditAnnouncementModal from '../../Components/UserModals/EditAnnouncementModal.vue';

const props = defineProps({
   announcements: (Array, Object)
});

const formatDate = (dateString) => {
   return new Date(dateString).toLocaleDateString('en-US', {
      month: 'short', 
      day: 'numeric', 
      year: 'numeric'
   })
}

const form = useForm({
   title: '',
   content: '',
})

const deleteForm = useForm({
   id: '',
   title: ''
});

const submitAnnouncement = () => {
   form.post(route('announcements.store'), {
      onSuccess: () => {
         form.reset()
      },
      preserveScroll: true
   })
}

const openDeleteModal = (announcement) => {
   deleteForm.id = announcement.id;
   deleteForm.title = announcement.title;
};

const deleteAnnouncement = () => {
   deleteForm.delete(route('announcements.destroy', deleteForm.id), {
      onSuccess: () => {
         const modalElement = document.querySelector('#deleteModal');
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