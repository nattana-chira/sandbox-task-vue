import { createRouter, createWebHistory } from 'vue-router';
import TechnicianList from './components/TechnicianList.vue';
import TechnicianCreate from './components/TechnicianCreate.vue';
import TechnicianEdit from './components/TechnicianEdit.vue';
import TaskList from './components/TaskList.vue';
import TaskCreate from './components/TaskCreate.vue';
import TaskEdit from './components/TaskEdit.vue';

const routes = [
  {
    path: '/',
    name: 'technician-list',
    component: TechnicianList,
  },
  {
    path: '/technician/create',
    name: 'create-technician',
    component: TechnicianCreate,
  },
  {
    path: '/technician/edit/:id',
    name: 'edit-technician',
    component: TechnicianEdit,
    props: true,  // This allows the route parameter to be passed as a prop
},
  { 
    path: '/tasks', 
    name: 'task-list',
    component: TaskList 
  },
  { 
    path: '/task/create', 
    name: 'create-task',
    component: TaskCreate 
  },
  { 
    path: 
    '/task/edit/:id', 
    name: 'edit-task',
    component: TaskEdit,
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;