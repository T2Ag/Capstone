<template>
   <Layout>
     <div class="p-6">
       <div class="flex justify-end my-2">
         <button type="button" class="rounded text-white px-3 py-2 bg-red-700" data-bs-toggle="modal" data-bs-target="#createModal">
           Add User
         </button>
       </div>
 
       <CreateUserModal :user_roles="user_roles" />
 
       <div class="bg-white shadow-md rounded overflow-hidden p-3">
         <table class="w-full text-left text-gray-500 bg-white">
           <thead class="text-l text-700 uppercase bg-gray-100">
             <tr class="text-center">
               <th scope="col" class="lg:px-5 px-3 py-3">ID</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Username</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Role</th>
               <th scope="col" class="lg:px-5 px-3 py-3">Actions</th>
             </tr>
           </thead>
           <tbody>
             <tr v-for="user in users" :key="user.id" class="text-center">
               <td>{{ user.id }}</td>
               <td>{{ user.username }}</td>
               <td class="capitalize">
                 <span v-if="user.roles && user.roles.length">
                   {{ user.roles.map(role => role.name).join(', ') }}
                 </span>
                 <span v-else>No roles assigned</span>
               </td>
               <td>
                 <button class="text-green-600 mx-2" type="button" @click="openEditModal(user)" :data-bs-toggle="'modal'" :data-bs-target="'#editModal'">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                     </svg>
                  </button>
 
                 <button class="text-red-600 mx-2" type="button" @click="openDeleteModal(user)" data-bs-toggle="modal" data-bs-target="#deleteModal">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                     </svg>  
                 </button>
 
                 <!-- Edit User Modal -->
                 <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalTitle" aria-hidden="true">
                   <div class="modal-dialog modal-dialog-centered">
                     <div class="modal-content">
                       <div class="modal-header">
                         <h5 class="modal-title" id="editModalTitle">Edit User</h5>
                         <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                       </div>
                       <form @submit.prevent="update" class="text-left">
                           <div class="modal-body">
                              <div class="mb-3">
                                 <label for="username" class="form-label">Username</label>
                                 <input type="text" class="form-control" id="username" name="username" v-model="editForm.username">
                                 <span class="text-red-500">{{ editForm.errors.username }}</span>
                              </div>

                              <div class="mb-3">
                                 <label for="password" class="form-label">Password</label>
                                 <input type="password" class="form-control" id="password" name="password" v-model="editForm.password">
                                 <span class="text-red-500">{{ editForm.errors.password }}</span>
                              </div>

                              <div class="mb-3">
                                 <label for="password_confirmation" class="form-label">Confirm Password</label>
                                 <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" v-model="editForm.password_confirmation">
                                 <span class="text-red-500">{{ editForm.errors.password_confirmation }}</span>
                              </div>

                              <div class="mb-3">
                                 <label for="role" class="form-label">Roles</label>
                                 <div v-for="role in user_roles" :key="role.id" class="form-check">
                                    <input class="form-check-input" type="radio" :id="'role-' + role.id" :value="role.name" v-model="editForm.role">
                                    <label class="form-check-label capitalize" :for="'role-' + role.id">
                                       {{ role.name }}
                                    </label>
                                 </div>
                                 <span v-if="editForm.errors.role" class="text-red-500">{{ editForm.errors.role }}</span>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Update User</button>
                           </div>
                        </form>
                     </div>
                   </div>
                 </div>

                  <!-- Delete User Modal -->
                  <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalTitle" aria-hidden="true">
                     <div class="modal-dialog modal-dialog-centered">
                     <div class="modal-content">
                        <div class="modal-header">
                           <h5 class="modal-title" id="deleteModalTitle">Delete User</h5>
                           <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                           Are you sure you want to delete user # - {{ deleteForm.id }} - {{ deleteForm.username }}?
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                           <button type="button" class="btn btn-danger" @click="deleteUser">Delete</button>
                        </div>
                     </div>
                     </div>
                  </div>

               </td>
             </tr>
           </tbody>
         </table>
       </div>
     </div>
   </Layout>
 </template>
 
 <script setup>
 import { ref } from 'vue';
 import { useForm } from '@inertiajs/vue3';
 import Layout from '@/Layouts/Layout.vue';
 import CreateUserModal from '@/Components/UserModals/CreateUserModal.vue';
 
 const props = defineProps({
   users: Array,
   user_roles: Array
 });
 
 // Edit Form
 const editForm = useForm({
   id: null,
   username: '',
   password: '',
   password_confirmation: '',
   role: ''
 });
 
 const openEditModal = (user) => {
   editForm.id = user.id;
   editForm.username = user.username;
   editForm.password = '';
   editForm.password_confirmation = '';
   editForm.role = user.roles && user.roles.length ? user.roles[0].name : '';
 };
 
 const update = () => {
   editForm.errors = {};
 
   editForm.put(route('users.update', editForm.id), {
     onError: (errors) => {
       editForm.errors = errors;
     },
     onSuccess: () => {
       editForm.reset();
       const modalElement = document.querySelector('#editModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
     }
   });
 };
 
 // Delete
 const deleteForm = useForm({
   id: null,
   username: ''
 });
 
 const openDeleteModal = (user) => {
   deleteForm.id = user.id;
   deleteForm.username = user.username;
 };

 const deleteUser = () => {
   deleteForm.delete(route('users.destroy', deleteForm.id), {
     onError: (errors) => {
       console.error(errors);
     },
     onSuccess: () => {
       const modalElement = document.querySelector('#deleteModal');
       if (modalElement) {
         const modal = bootstrap.Modal.getInstance(modalElement);
         if (modal) {
           modal.hide();
         }
       }
     }
   });
 };
 </script>
