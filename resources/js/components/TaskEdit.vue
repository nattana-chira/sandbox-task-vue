<template>
  <div class="w-full">
    <h1 class="text-2xl font-semibold mb-4">Edit Task</h1>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
        <input
          type="text"
          id="title"
          v-model="form.title"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          required
        />
      </div>

      <div class="mb-4">
        <label for="required_skill" class="block text-sm font-medium text-gray-700">Required Skill</label>
        <input
          type="text"
          id="required_skill"
          v-model="form.required_skill"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          required
        />
      </div>

      <div class="mb-4">
        <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
        <select
          id="urgency"
          v-model="form.urgency"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="duration" class="block text-sm font-medium text-gray-700">Duration</label>
        <input
          type="number"
          id="duration"
          v-model="form.duration"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          min="1"
          max="3"
          required
        />
      </div>

      <div class="mb-4">
        <label for="required_technicians" class="block text-sm font-medium text-gray-700">Required Technicians</label>
        <input
          type="number"
          id="required_technicians"
          v-model="form.required_technicians"
          class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          min="1"
          required
        />
      </div>

      <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Task</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      form: {
        title: '',
        required_skill: '',
        urgency: 'Medium',
        required_technicians: '',
        duration: '',
        technicians: [],
      },
      errors: [],
    };
  },
  mounted() {
    // Fetch the task data and the list of technicians
    axios.get(`/api/tasks/${this.$route.params.id}`)
      .then(response => {
        this.form = response.data;
      });
  },
  methods: {
    async submitForm() {
      try {
        const response = await axios.put(`/api/tasks/${this.$route.params.id}`, this.form)
        this.$router.push('/tasks');

      } catch (error) {
        console.error('Error editing task:', error);
        alert(error?.response?.data?.message)
      }
    }
  }
};
</script>