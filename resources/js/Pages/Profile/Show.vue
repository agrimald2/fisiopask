<template>
  <app-layout title="Profile">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">



        <input type="file"
          class="py-2 px-4 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
          @change="handleFileUpload" />
        <jet-section-border />

        <div v-if="$page.props.jetstream.canUpdateProfileInformation">
          <update-profile-information-form :user="$page.props.user" />

          <jet-section-border />
        </div>

        <div v-if="$page.props.jetstream.canUpdatePassword">
          <update-password-form class="mt-10 sm:mt-0" />

          <jet-section-border />
        </div>

        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
          <two-factor-authentication-form class="mt-10 sm:mt-0" />

          <jet-section-border />
        </div>

        <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

        <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
          <jet-section-border />

          <delete-user-form class="mt-10 sm:mt-0" />
        </template>

        <template>
          <jet-section-border />

          <update-rate class="mt-10 sm:mt-0" />
        </template>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteUserForm from "@/Pages/Profile/Partials/DeleteUserForm.vue";
import UpdateRate from "@/Pages/Profile/Partials/UpdateRate.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import GoogleCalendarConnection from "./Partials/GoogleCalendarConnection.vue";

export default {
  props: ["sessions"],

  components: {
    AppLayout,
    DeleteUserForm,
    JetSectionBorder,
    LogoutOtherBrowserSessionsForm,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
    UpdateRate,
    GoogleCalendarConnection,
  },
  methods: {
    handleFileUpload(event) {
      const file = event.target.files[0];

      const formData = new FormData();
      formData.append('file', file);

      axios.post('/rate/file/store', formData)
        .then(response => {
          // Handle success response
        })
        .catch(error => {
          // Handle error response
        });
    }
  }
};
</script>
