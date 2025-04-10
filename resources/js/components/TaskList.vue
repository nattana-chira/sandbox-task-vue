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

    <h1 class="text-2xl font-semibold mb-4">Tasks</h1>

    <div class="mb-4">
      <router-link to="/task/create" class="bg-blue-500 text-white rounded p-2">Add Task</router-link>
    </div>

    <!-- Match Button -->
    <div class="mb-4">
      <button 
        @click="matchTechnicians" 
        class="bg-green-500 text-white rounded p-2"
      >
        Match Technicians to Tasks
      </button>
    </div>

    <table class="min-w-full table-auto border-collapse">
      <thead>
        <tr class="bg-gray-100">
          <th class="px-4 py-2 text-left border">Title</th>
          <th class="px-4 py-2 text-left border">Urgency</th>
          <th class="px-4 py-2 text-left border">Duration</th>
          <th class="px-4 py-2 text-left border">Required Skill</th>
          <th class="px-4 py-2 text-left border">Technicians</th>
          <th class="px-4 py-2 text-center border max-w-38 min-w-38 w-38">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="task in tasks" :key="task.id">
          <td class="px-4 py-2 border">{{ task.title }}</td>
          <td class="px-4 py-2 border">{{ task.urgency }}</td>
          <td class="px-4 py-2 border">{{ task.duration }}</td>
          <td class="px-4 py-2 border">{{ task.required_skill }}</td>
          <td class="px-4 py-2 border">{{ task.required_technicians }}</td>
          <td class="px-4 py-2 border text-right">
            <router-link :to="'/task/edit/' + task.id" class="bg-green-500 text-white rounded p-2 inline-block">Edit</router-link>
            <button @click="deleteTask(task.id)" class="ml-2 bg-red-500 text-white rounded p-2 inline-block">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-10">
      <h1 class="text-2xl font-semibold mb-4">Match Result:</h1>
    
      <table class="min-w-full table-auto">
        <thead>
          <tr class="border">
            <th class="px-4 py-2 border">Task Title</th>
            <th class="px-4 py-2 border">Technicians</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="task in matchTasks" :key="task.id" class="border">
            <td class="px-4 py-2 border">{{ task.title }}</td>
            <td class="px-4 py-2 border">
              <ul>
                <li v-for="technician in task.assignedTechnicians" :key="technician.id">
                  {{ technician.name }} - Days: {{ technician.workDays }}
                </li>
              </ul>
            </td>
          </tr>
        </tbody>
      </table>

    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tasks: [],
      matchTasks: [],
      technicianWorkDays: {},
      totalWorkDays: 0
    };
  },
  mounted() {
    axios.get('/api/tasks')
      .then(response => {
        console.log(response.data)
        this.tasks = response.data;
      });

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
    deleteTask(taskId) {
      axios.delete(`/api/tasks/${taskId}`)
        .then(() => {
          this.tasks = this.tasks.filter(task => task.id !== taskId);
          alert('Delete task successfully')
        });
    },
    // Trigger the match process
    matchTechnicians() {
      this.matchTasks = []

      axios.get('/api/tasks-match')
        .then(response => {
          // Assuming response contains matched tasks and technicians
          this.matchTasks = response.data.tasks;
          console.log("data tasks ", response.data)
          alert("Technicians matched successfully!")
        })
        .catch(error => {
          console.error(error);
          alert(error?.response?.data?.message || error?.response?.data?.error)
        });
    }
  }
};
</script>