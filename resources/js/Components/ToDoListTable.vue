<template>
   <div class="mt-6">
     <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-6">
         <h2 class="text-xl font-semibold mb-4">Todo List</h2>

         <!-- Add Todo Form -->
         <form @submit.prevent="addTodo" class="flex gap-2 mb-5">
            <input 
               v-model="newTodo"
               type="text"
               placeholder="Add new todo..."
               class="rounded-md border-gray-300 shadow-sm p-2 border w-[30rem]"
            >
            <button 
               type="submit"
               class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
               Add
            </button>
         </form>
         
         <div class="overflow-x-auto">
           <table class="min-w-full table-auto">
             <thead>
               <tr class="bg-gray-100">
                 <th class="px-4 py-2 text-left">Status</th>
                 <th class="px-4 py-2 text-left">Title</th>
                 <th class="px-4 py-2 text-left">Created</th>
                 <th class="px-4 py-2 text-left">Updated</th>
                 <th class="px-4 py-2 text-left">Actions</th>

               </tr>
             </thead>
             <tbody>
               <tr v-for="todo in todos" :key="todo.id" class="border-b hover:bg-gray-50">
                 <td class="px-4 py-2">
                   <input 
                     type="checkbox" 
                     :checked="todo.is_completed"
                     @change="updateTodoStatus(todo)"
                     class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                   >
                 </td>
                 <td class="px-4 py-2" :class="{ 'line-through text-gray-500': todo.is_completed }">
                   {{ todo.title }}
                 </td>
                 <td class="px-4 py-2 text-sm text-gray-600">
                   {{ formatDate(todo.created_at) }}
                 </td>
                 <td class="px-4 py-2 text-sm text-gray-600">
                   {{ formatDate(todo.updated_at) }}
                 </td>

                 <td>
                     <button type="button" class="rounded text-green-500 text-[15px]" @click="openEditModal(todo)" data-bs-toggle="modal" data-bs-target="#editTodoModal">
                        <i class="bi bi-pencil"></i>
                     </button>
                 </td>
               </tr>
               <tr v-if="todos.length === 0">
                 <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                   No todos found for this client.
                 </td>
               </tr>
             </tbody>
           </table>
         </div>
       </div>
     </div>
   </div>

   <!-- Bootstrap Modal -->
   <div class="modal fade" id="editTodoModal" tabindex="-1" aria-labelledby="editTodoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTodoModalLabel">Edit Todo</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form @submit.prevent="submit">
            <div class="modal-body">
               <input
               type="text"
               v-model="editForm.title"
               class="form-control"
               placeholder="Edit todo title"
               >
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>
      </div>
   </div>   

 </template>
 
 <script setup>
 import { ref } from 'vue';
 import { router, useForm } from '@inertiajs/vue3';
 
 const props = defineProps({
   todos: Array,
   client: Object
 });

const newTodo = ref('');
 
function formatDate(dateString) {
const options = { year: 'numeric', month: 'long', day: 'numeric' };
return new Date(dateString).toLocaleDateString(undefined, options);
}

const addTodo = () => {
  if (!newTodo.value.trim()) return;

  router.post(route('toDoList.store'), {
    title: newTodo.value,
    client_id: props.client.id
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      newTodo.value = '';
    }
  });
};

const updateTodoStatus = (todo) => {
   router.put(route('toDoList.update', todo.id), {
      is_completed: !todo.is_completed
   }, {
      preserveScroll: true,
      preserveState: true
   });
};

const editForm = useForm({
   id: '', 
   title: '',
   is_completed: ''
})

const openEditModal = (todo) => {
   editForm.id = todo.id;
   editForm.title = todo.title;
   editForm.is_completed = todo.is_completed;
}

const submit = () => {
   editForm.put(route('toDoList.update', editForm.id), {
     onSuccess: () => {
      editForm.reset();
       const modalElement = document.querySelector(`#editTodoModal`);
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