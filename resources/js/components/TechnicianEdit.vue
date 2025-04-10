<template>
  <div>
    <h1 class="text-2xl font-semibold mb-4">Edit Technician</h1>

    <form @submit.prevent="submitForm">
      <!-- Name Field -->
      <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Technician Name</label>
        <input
          type="text"
          v-model="form.name"
          id="name"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Enter technician's name"
        />
      </div>

      <!-- Skills Field -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700">Skills</label>
        <div v-for="(skill, index) in form.skills" :key="index" class="mb-2 flex items-center space-x-2">
          <input
            type="text"
            v-model="form.skills[index]"
            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            placeholder="Skill"
          />
          <button
            type="button"
            @click="removeSkill(index)"
            class="px-2 py-1 text-sm text-white bg-red-500 rounded-md hover:bg-red-600"
          >
            Remove
          </button>
        </div>
        <button
          type="button"
          @click="addSkill"
          class="text-sm text-indigo-600 hover:text-indigo-700"
        >
          Add Skill
        </button>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="w-full py-2 mt-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
      >
        Update Technician
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: '',
        skills: [''], // Initialize with one empty skill
      },
    };
  },
  mounted() {
    this.fetchTechnician();
  },
  methods: {
    async fetchTechnician() {
      try {
        const response = await axios.get(`/api/technicians/${this.$route.params.id}`);
        this.form.name = response.data.name;
        this.form.skills = response.data.skills?.map(skill => skill.name) || [];
      } catch (error) {
        console.error('Error fetching technician:', error);
      }
    },
    addSkill() {
      this.form.skills.push('');
    },
    removeSkill(index) {
      this.form.skills.splice(index, 1);
    },
    async submitForm() {
      try {
        await axios.put(`/api/technicians/${this.$route.params.id}`, this.form);
        this.$router.push('/'); // Redirect to technician list after update
      } catch (error) {
        console.error('Error updating technician:', error);
        alert(error?.response?.data?.message)
      }
    },
  },
};
</script>