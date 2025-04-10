<template>
  <div class="w-full">
    <h1 class="text-2xl font-semibold mb-4">Create Task</h1>

    <form @submit.prevent="submitForm">
      <div class="mb-4">
        <label for="title" class="block">Title</label>
        <input type="text" id="title" v-model="form.title" class="w-full p-2 border rounded" required>
      </div>

      <div class="mb-4">
        <label for="required_skill" class="block">Required Skill</label>
        <input type="text" id="required_skill" v-model="form.required_skill" class="w-full p-2 border rounded" required>
      </div>

      <div class="mb-4">
        <label for="urgency" class="block">Urgency</label>
        <select id="urgency" v-model="form.urgency" class="w-full p-2 border rounded" required>
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
        </select>
      </div>

      <div class="mb-4">
        <label for="required_technicians" class="block">Required Technicians</label>
        <input type="number" id="required_technicians" v-model="form.required_technicians" class="w-full p-2 border rounded" required>
      </div>

      <div class="mb-4">
        <label for="duration" class="block">Duration</label>
        <input type="number" id="duration" v-model="form.duration" class="w-full p-2 border rounded" required>
      </div>

      <button type="submit" class="bg-blue-500 text-white p-2 rounded">Create Task</button>
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
        urgency: 'Low'
      },
    };
  },
  mounted() {
  },
  methods: {
    async submitForm() {
      try {
        event.preventDefault()
        const response = await axios.post('/api/tasks', this.form)
        this.$router.push('/tasks');

      } catch (error) {
        console.error('Error creating task:', error);
        alert(error?.response?.data?.message)
      }
    }
  }
};
</script>