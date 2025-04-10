<template>
  <div class="w-160">
    <div class="flex gap-3 mb-8">
      <!-- Button to Navigate to Tasks Page -->
      <router-link to="/tasks" class="bg-yellow-500 text-white p-2 rounded">
        View Tasks
      </router-link>

      <!-- Button to Navigate to Technicians Page -->
      <router-link to="/" class="bg-orange-500 text-white p-2 rounded">
        View Technicians
      </router-link>
    </div>

    <h1 class="text-2xl font-semibold mb-4">Technicians</h1>

    <div class="mb-4">
      <router-link to="/technician/create" class="bg-blue-500 text-white rounded p-2">Add Technician</router-link>
    </div>

    <table class="min-w-full table-auto border">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-1 text-left border">Name</th>
          <th class="px-4 py-1 text-left border">Skills</th>
          <th class="px-4 py-1 border text-center max-w-38 min-w-38 w-38">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="technician in technicians" :key="technician.id">
          <td class="px-4 py-1 border">{{ technician.name }}</td>
          <td class="px-4 py-1 border">{{ technician.skills.map(skill => skill.name).join(', ') }}</td>
          <td class="px-4 py-1 border text-right">
            <router-link :to="'/technician/edit/' + technician.id" class="bg-green-500 text-white rounded p-2 inline-block">Edit</router-link>
            <button @click="deleteTechnician(technician.id)" class="ml-2 bg-red-500 text-white rounded p-2 inline-block">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      technicians: [],  // This will hold the list of technicians
    };
  },
  mounted() {
    this.fetchTechnicians();
  },
  methods: {
    async fetchTechnicians() {
      try {
        const response = await axios.get('/api/technicians');
        console.log(response.data)
        this.technicians = response.data;
      } catch (error) {
        console.error('Error fetching technicians:', error);
      }
    },
    async deleteTechnician(id) {
      if (confirm('Are you sure you want to delete this technician?')) {
        try {
          await axios.delete(`/api/technicians/${id}`);
          this.fetchTechnicians();  // Refresh the list after deleting
        } catch (error) {
          console.error('Error deleting technician:', error);
        }
      }
    },
  },
};
</script>