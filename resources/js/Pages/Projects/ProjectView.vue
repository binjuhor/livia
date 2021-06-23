<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                View Project {{project.name}}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <issue-list :issues="project.issues" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import IssueList from '@/Pages/Projects/IssueList'

    export default {
        props: [
            'project'
        ],

        components: {
            AppLayout,
            IssueList
        },
    }
</script>
