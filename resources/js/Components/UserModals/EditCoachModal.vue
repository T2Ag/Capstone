<template>
  <div class="modal fade" :id="`editModal-${coach.id}`" tabindex="-1" role="dialog" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header flex justify-between">
          <h5 class="modal-title" id="editModalTitle">Edit coach</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form @submit.prevent="submit">
          <div class="modal-body">
              <div class="row">
                <!-- First Name -->
                <div class="col-6 mb-3">
                  <label :for="`first_name-${coach.id}`" class="form-label">First Name</label>
                  <input type="text" class="form-control" :id="`first_name-${coach.id}`" name="first_name" v-model="form.first_name">
                  <span class="text-red-500">{{ form.errors.first_name }}</span>
                </div>

                <!-- Last Name -->
                <div class="col-6 mb-3">
                  <label :for="`last_name-${coach.id}`" class="form-label">Last Name</label>
                  <input type="text" class="form-control" :id="`last_name-${coach.id}`" name="last_name" v-model="form.last_name">
                  <span class="text-red-500">{{ form.errors.last_name }}</span>
                </div>

                <!-- Middle Initial -->
                <div class="col-6 mb-3">
                  <label :for="`middle_initial-${coach.id}`" class="form-label">Middle Initial</label>
                  <input type="text" class="form-control" :id="`middle_initial-${coach.id}`" name="middle_initial" v-model="form.middle_initial" @input="capitalizeMiddleInitial" maxlength="1">
                  <span class="text-red-500">{{ form.errors.middle_initial }}</span>
                </div>

                <!-- Gender -->
                <div class="col-6 mb-3">
                  <label :for="`gender-${coach.id}`" class="form-label">Gender</label>
                  <select class="form-control" :id="`gender-${coach.id}`" name="gender" v-model="form.gender">
                    <option value="" disabled>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                  </select>
                  <span class="text-red-500">{{ form.errors.gender }}</span>
                </div>

              </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Coach</button>
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
  coach: {
    type: Object,
    required: true
  }
});

const form = useForm({
 first_name: props.coach.first_name,
 last_name: props.coach.last_name,
 middle_initial: props.coach.middle_initial,
 gender: props.coach.gender,
});

const resetForm = () => {
  form.first_name = props.coach.first_name;
  form.last_name = props.coach.last_name;
  form.middle_initial = props.coach.middle_initial;
  form.gender = props.coach.gender;
};

onMounted(() => {
  const modalElement = document.querySelector(`#editModal-${props.coach.id}`);
  if (modalElement) {
    modalElement.addEventListener('show.bs.modal', resetForm);
  }
});

const capitalizeMiddleInitial = (event) => {
  form.middle_initial = event.target.value.toUpperCase();
};

const submit = () => {
 form.put(route('coaches.update', props.coach.id), {
    onSuccess: () => {
      resetForm(); 
      const modalElement = document.querySelector(`#editModal-${props.coach.id}`);
      if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
      }
    },
    onError: (errors) => {
      errors.value = errors;
    }
 });
};
</script>