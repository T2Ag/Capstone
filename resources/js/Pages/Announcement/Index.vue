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
      <div class="bg-white mt-4 m-3">
         <div class="px-3 py-2 border-b-2 border-gray-400">
            <p class="text-[20px] text-gray-600 font-bold">ANNOUNCEMENTS</p>
         </div>

         <table class="w-full">
            <thead class="bg-gray-100">
               <tr>
                  <th class="px-4 py-2 text-left text-gray-600">Content</th>
                  <th class="px-4 py-2 text-left text-gray-600">Date</th>
                  <th class="px-4 py-2 text-left text-gray-600">Actions</th>

               </tr>
            </thead>
            <tbody>
               <tr 
                  v-for="announcement in announcements.data" 
                  :key="announcement.id"
                  class="border-b hover:bg-gray-50"
               >
                  <td class="px-4 py-2 flex flex-col">
                     <div class="text-[20px] font-bold my-2">{{ announcement.title }}</div>
                     <div class="mx-2">{{ announcement.content }}</div>
                  </td>
                  <td class="px-4 w-[10rem]">
                     {{ formatDate(announcement.created_at) }}
                  </td>
                  <td>
                     <button 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded mr-2" data-bs-toggle="modal" :data-bs-target="`#editAnnouncementModal-${announcement.id}`"
                     >
                       Edit
                     </button>

                     <button 
                       class="text-red-600 mx-2" 
                       type="button" 
                       @click="openDeleteModal(announcement)" 
                       data-bs-toggle="modal" 
                       data-bs-target="#deleteModal"
                     >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                           <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg>
                     </button>
                  </td>
               </tr>
            </tbody>
         </table>

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