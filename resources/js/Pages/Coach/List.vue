<template>

   <Layout>
   <div class="p-3">
      <div class="flex justify-between">
         <div class="">
            <p class="text-3xl font-bold text-gray-900 ">Coach List</p>
         </div>
         <div class="">
            <form @input="filterCoaches">
               <InputField
                  type="search"
                  label=""
                  icon="search"
                  placeholder="Search..."
                  v-model="form.search"
               />
            </form>
         </div>
         
      </div>

      <div v-if="success" class="px-2 py-1 mt-3 bg-green-200 rounded border-1 border-green-500 ">
         <SuccessMessages :success="success" class="p-3"/>
      </div>   
   
      <div class="px-2 py-2">
   
         <div class="flex justify-end my-2">
            <button type="button" class="rounded px-4 py-2 bg-gradient-to-r from-red-500 to-red-700 text-white font-semibold shadow-md" data-bs-toggle="modal" data-bs-target="#createModal">
               Register Coach
            </button>
         </div>
   
         <CreateCoachModal/>
   
         <div class="bg-white shadow-md rounded overflow-hidden p-3">
   
            <table class="w-full text-left text-gray-700 bg-white">
               <thead class="text-l text-700 uppercase bg-gray-100">
                  <tr class="text-center">
   
                     <th scope="col" class="lg:px-5 px-3 py-3">ID</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Name</th>
                     <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
   
                  </tr>
               </thead>
               <tbody>
                  <tr v-for="coach in coaches.data" :key="coach.id" class="text-center ">
                     <td>{{ coach.id }}</td>
                     <td>  {{ coach.first_name }} {{ coach.middle_initial }}. {{ coach.last_name }} </td>
                     <td class="flex items-center justify-center p-2">

                        <Link :href="route('coaches.view',  { coach: coach.id })" >
                           <i class="bi bi-eye text-[1.5rem] "></i>
                        </Link>
   
                        <button type="button" class="rounded text-green-500 text-[15px] mx-3 " :data-bs-toggle="'modal'" :data-bs-target="`#editModal-${coach.id}`">
                           <i class="bi bi-pencil"></i>
                        </button>
   
                        <EditCoachModal :coach="coach" :key="`editModal-${coach.id}`" />
   
                        <button class="text-red-600 " type="button" @click="openDeleteModal(coach)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                           <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                              <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                           </svg>
                        </button>
                        
                     </td>
                  </tr>
               </tbody>
            </table>
   
            <Pagination class="flex mt-4 justify-end" :links="coaches.links" />
   
            </div>
      </div>
   
      <!-- Delete User Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
         <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title" id="deleteModalTitle">Delete Coach</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                  Are you sure you want to delete coach # - {{ deleteForm.id }} - {{ deleteForm.last_name }}, {{ deleteForm.first_name }} {{ deleteForm.middle_initial }}?
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-danger" @click="deleteCoach">Delete</button>
               </div>
            </div>
         </div>
      </div>
   </div>
      
   </Layout>
   
   </template>
   
   <script setup>
   
   import Layout from '@/Layouts/Layout.vue';
   import CreateCoachModal from '../../Components/UserModals/CreateCoachModal.vue';
   import EditCoachModal from '../../Components/UserModals/EditCoachModal.vue';
   import SuccessMessages from '../../Components/SuccessMessages.vue';
   import InputField from '../../Components/InputField.vue';
   import Pagination from '../../Components/Pagination.vue';
   import { useForm, router } from '@inertiajs/vue3';
   import {ref, computed} from 'vue';
   
   const props = defineProps({
      coaches: (Array, Object),
      search: String,
      success: String
   })
   
   const deleteForm = useForm({
      id: null, 
      first_name: '',
      last_name: '',
      middle_initial: '',
   })
   
   const openDeleteModal = (coach) => {
      deleteForm.id = coach.id;
      deleteForm.first_name = coach.first_name;
      deleteForm.last_name = coach.last_name;
      deleteForm.middle_initial = coach.middle_initial;
   }
   
   const deleteCoach = () => {
      deleteForm.delete(route('coaches.destroy', deleteForm.id), {
         onError: (errors) => {
            console.error(errors);
         },
         onSuccess: () => {
            const modalElement = document.querySelector('#deleteModal');
            if(modalElement) {
               const modal = bootstrap.Modal.getInstance(modalElement);
               if (modal) {
                  modal.hide();
               }
            }
         }
      });
   };

   const form = useForm({
      search : props.search || ''
   })

   const filterCoaches = () => {
      router.get(route('coaches.index'), { 
         search: form.search
      }, {
         preserveState: true,
         preserveScroll: true,
      });
   };
   
   </script>