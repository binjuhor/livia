<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Projects
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <project-list :projects="projects"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import ProjectList from '@/Pages/Projects/List'

    export default {
        props: [
            'projects'
        ],

        components: {
            AppLayout,
            ProjectList
        },
    }
</script>
