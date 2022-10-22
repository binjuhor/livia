<script setup>
import AppLayout from '../Layouts/AppLayout.vue'
import JetSecondaryButton from '../Components/SecondaryButton.vue'
import JetButton from '../Components/SecondaryButton.vue'
import {useForm} from '@inertiajs/inertia-vue3'
import Pagination from '../Components/Pagination.vue'
import InvoiceStatus from '../Components/InvoiceStatus.vue'

const props = defineProps({
  project: Object,
  issues: Object
})

const createInvoiceForm = useForm({})

const syncProjectForm = useForm({
  jira_key: props.project.jira_key
})

const createInvoice = () => {
  createInvoiceForm.post(route('projects.createInvoice', props.project), {
    errorBag: 'createInvoice',
    preserveScroll: true,
    onSuccess: () => createInvoiceForm.reset(),
  });
}

const syncProject = () => {
  syncProjectForm.post(route('projects.syncJira'), {
    errorBag: 'syncJiraProject',
    preserveScroll: true,
    onSuccess: () => syncProjectForm.reset(),
  });
}

const typeLabel = type => ['Story', 'Task', 'Bug'][type]

const statusLabel = status => ['To Do', 'Working', 'Done'][status]

</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        View project {{ project.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <section class="actions">
            <jet-secondary-button class="ml-2" @click="syncProject"
                                  :class="{ 'opacity-25': syncProjectForm.processing }"
                                  :disabled="syncProjectForm.processing">
              Sync Issues
            </jet-secondary-button>

            <jet-button class="ml-2" @click="createInvoice" :class="{ 'opacity-25': createInvoiceForm.processing }"
                        :disabled="createInvoiceForm.processing">
              Create Invoice
            </jet-button>
          </section>

          <section class="issues">
            <h3>Issues</h3>
            <div class="space-y-6">
              <div class="grid grid-flow-col grid-cols-10">
                <div class="text-gray-600">Jira Key</div>
                <div class="text-gray-600 col-span-6">Summary</div>
                <div class="text-gray-600">Points</div>
                <div class="text-gray-600">Type</div>
                <div class="text-gray-600">Status</div>
              </div>
              <div v-if="issues.data.length > 0">
                <div class="grid grid-flow-col grid-cols-10" v-for="issue in issues.data" :key="issue.id">
                  <div class="text-gray-600">{{ issue.jira_key }}</div>
                  <div class="text-gray-600 col-span-6">{{ issue.summary }}</div>
                  <div class="text-gray-600">{{ issue.story_point }}</div>
                  <div class="text-gray-600">{{ typeLabel(issue.type) }}</div>
                  <div class="text-gray-600">{{ statusLabel(issue.status) }}</div>
                </div>

                <div class="flex flex-row-reverse mt-6">
                  <pagination :links="issues.links"/>
                </div>
              </div>
              <div v-else>
                <div class="flex items-center justify-between">
                  We have no issue for this project at the moment.
                </div>
              </div>
            </div>
          </section>

          <section class="invoices">
            <h3>Invoices</h3>
            <div class="space-y-6">
              <div v-if="project.invoices.length > 0">
                <div class="flex items-center justify-between" v-for="invoice in project.invoices"
                     :key="invoice.id">
                  <div class="text-gray-600">{{ invoice.reference }}</div>
                  <div class="text-gray-600">
                    <a v-if="invoice.pdf_file" :href="invoice.pdf_file" target="_blank" class="flex">
                      <svg class="svg-icon" viewBox="0 0 20 20">
                        <path fill="none" d="M17.222,5.041l-4.443-4.414c-0.152-0.151-0.356-0.235-0.571-0.235h-8.86c-0.444,0-0.807,0.361-0.807,0.808v17.602c0,0.448,0.363,0.808,0.807,0.808h13.303c0.448,0,0.808-0.36,0.808-0.808V5.615C17.459,5.399,17.373,5.192,17.222,5.041zM15.843,17.993H4.157V2.007h7.72l3.966,3.942V17.993z"></path>
                        <path fill="none" d="M5.112,7.3c0,0.446,0.363,0.808,0.808,0.808h8.077c0.445,0,0.808-0.361,0.808-0.808c0-0.447-0.363-0.808-0.808-0.808H5.92C5.475,6.492,5.112,6.853,5.112,7.3z"></path>
                        <path fill="none" d="M5.92,5.331h4.342c0.445,0,0.808-0.361,0.808-0.808c0-0.446-0.363-0.808-0.808-0.808H5.92c-0.444,0-0.808,0.361-0.808,0.808C5.112,4.97,5.475,5.331,5.92,5.331z"></path>
                        <path fill="none" d="M13.997,9.218H5.92c-0.444,0-0.808,0.361-0.808,0.808c0,0.446,0.363,0.808,0.808,0.808h8.077c0.445,0,0.808-0.361,0.808-0.808C14.805,9.58,14.442,9.218,13.997,9.218z"></path>
                        <path fill="none" d="M13.997,11.944H5.92c-0.444,0-0.808,0.361-0.808,0.808c0,0.446,0.363,0.808,0.808,0.808h8.077c0.445,0,0.808-0.361,0.808-0.808C14.805,12.306,14.442,11.944,13.997,11.944z"></path>
                        <path fill="none" d="M13.997,14.67H5.92c-0.444,0-0.808,0.361-0.808,0.808c0,0.447,0.363,0.808,0.808,0.808h8.077c0.445,0,0.808-0.361,0.808-0.808C14.805,15.032,14.442,14.67,13.997,14.67z"></path>
                      </svg>
                      <span class="text-sm ml-1">PDF</span>
                    </a>
                  </div>
                  <div>
                    <invoice-status class="ml-1" :status="invoice.status" />
                  </div>
                  <div class="text-gray-600">{{ invoice.total }}</div>
                </div>
              </div>
              <div v-else>
                <div class="flex items-center justify-between">
                  We have no invoice for this project at the moment.
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </AppLayout>
</template>