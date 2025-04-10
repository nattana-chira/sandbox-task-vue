<template>
  <div>
    <h1 class="text-2xl font-semibold mb-4">Create Technician</h1>

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
        Create Technician
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
        skills: [''], // Start with one skill input
      },
    };
  },
  methods: {
    addSkill() {
      this.form.skills.push('');
    },
    removeSkill(index) {
      this.form.skills.splice(index, 1);
    },
    async submitForm(event) {
      try {
        event.preventDefault()
        const response = await axios.post('/api/technicians', this.form);
  
        this.$router.push('/');  // Redirect back to the technician list
      } catch (error) {
        console.error('Error creating technician:', error);
        alert(error?.response?.data?.message)
      }
    },
  },
};
</script>