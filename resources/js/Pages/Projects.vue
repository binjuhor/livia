<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <project-list :projects="projects" />

                <project-import-jira class="mt-10 sm:mt-0" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import ProjectList from '@/Pages/Projects/ProjectList'
    import ProjectImportJira from '@/Pages/Projects/ProjectImportJira'

    export default {
        props: [
            'projects'
        ],

        components: {
            AppLayout,
            ProjectList,
            ProjectImportJira
        },
    }
</script>
