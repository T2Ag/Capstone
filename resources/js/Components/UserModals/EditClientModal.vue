<template>
  <div
    class="modal fade"
    :id="`editModal-${client.id}`"
    tabindex="-1"
    role="dialog"
    aria-labelledby="editModalTitle"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header flex justify-between">
          <h5 class="modal-title" id="editModalTitle">Edit Client</h5>
          <button
            type="button"
            class="close"
            data-bs-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form @submit.prevent="submit">
          <div class="modal-body">
            <div class="row">
              <!-- First Name -->
              <div class="col-6 mb-3">
                <label for="first_name" class="form-label">First Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="first_name"
                  name="first_name"
                  v-model="form.first_name"
                />
                <span class="text-red-500">{{ form.errors.first_name }}</span>
              </div>

              <!-- Last Name -->
              <div class="col-6 mb-3">
                <label for="last_name" class="form-label">Last Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="last_name"
                  name="last_name"
                  v-model="form.last_name"
                />
                <span class="text-red-500">{{ form.errors.last_name }}</span>
              </div>

              <!-- Middle Initial -->
              <div class="col-6 mb-3">
                <label for="middle_initial" class="form-label">Middle Initial</label>
                <input
                  type="text"
                  class="form-control"
                  id="middle_initial"
                  name="middle_initial"
                  v-model="form.middle_initial"
                />
                <span class="text-red-500">{{ form.errors.middle_initial }}</span>
              </div>

              <!-- Gender -->
              <div class="col-6 mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select
                  class="form-control"
                  id="gender"
                  name="gender"
                  v-model="form.gender"
                >
                  <option value="" disabled>Select Gender</option>
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
                </select>
                <span class="text-red-500">{{ form.errors.gender }}</span>
              </div>

              <!-- Registration -->
              <div class="col-6 mb-3">
                <label for="registration_id" class="form-label">Registration</label>
                <select
                  class="form-control"
                  id="registration_id"
                  name="registration_id"
                  v-model="form.registration_id"
                >
                  <option value="" disabled>Select Registration</option>
                  <option
                    v-for="registration in registrations"
                    :key="registration.id"
                    :value="registration.id"
                  >
                    {{ registration.type }}
                  </option>
                </select>
                <span class="text-red-500">{{ form.errors.registration_id }}</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-bs-dismiss="modal"
            >
              Close
            </button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  client: Object,
  registrations: Array,
});

const form = useForm({
  first_name: props.client.first_name || "",
  last_name: props.client.last_name || "",
  middle_initial: props.client.middle_initial || "",
  gender: props.client.gender || "",
  registration_id: props.client.registration_id || "",
});

const submit = () => {
  form.put(route("clients.update", props.client.id), {
    onSuccess: () => {
      const modalElement = document.querySelector(
        `#editModal-${props.client.id}`
      );
      if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
          modal.hide();
        }
      }
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
};
</script>
