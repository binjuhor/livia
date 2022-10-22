<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

const props = defineProps({
  projects: Array
})
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="projects.length > 0">
            <div class="flex items-center justify-between" v-for="project in projects" :key="project.id">
              <div class="text-gray-600">{{ project.name }}</div>

              <div class="flex items-center">
                <!-- View Project Action -->
                <inertia-link :href="route('projects.view', project)"
                              class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                >
                  View
                </inertia-link>
              </div>
            </div>
          </div>
          <div v-else>
            <div class="flex items-center justify-between">
              We have no project at the moment.
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>