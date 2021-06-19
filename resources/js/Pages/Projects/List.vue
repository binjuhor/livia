<template>
    <div>
        <div v-if="true">
            <!-- Projects Listing -->
            <jet-action-section class="mt-10 sm:mt-0">
                <template #title>
                    Projects
                </template>

                <template #description>
                    Projects that we are working on.
                </template>

                <!-- List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-if="projects.length > 0">
                            <div class="flex items-center justify-between" v-for="project in projects" :key="project.id">
                                <div class="text-gray-600">{{ project.name }}</div>

                                <div class="flex items-center">
                                    <!-- View Project Action -->
                                    <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                            @click="viewProject(project)"
                                    >
                                        View
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <div class="flex items-center justify-between">
                                We have no project at the moment.
                            </div>
                        </div>
                    </div>
                </template>
            </jet-action-section>
        </div>
    </div>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        components: {
            JetActionSection,
        },

        props: [
            'projects'
        ],

        data() {
            return {

            }
        },

        methods: {
            viewProject(project) {
                alert(`View project ${project.name}`)
            }
        },
    }
</script>
