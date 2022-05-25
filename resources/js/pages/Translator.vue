<template>
  <div>
    <Head title="Translator"/>
    <Heading class="mb-6">Translator</Heading>
    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
      <div class="rounded-md shadow">
        <button @click="setLocale(locale)" v-for="locale in locales"
                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
          Load <b>{{ locale }}</b> strings
        </button>
      </div>
      <div class="mt-3 sm:mt-0 sm:ml-3">
        <button @click="update"
                class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
          Update translated strings
        </button>
      </div>
    </div>

    <div class="sm:text-center lg:text-left">
      <div v-for="translate in keys" v-bind:key="translate.key">
        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
          {{ translate.key }}</p>
        <div class="mt-1 relative rounded-md shadow-sm">
          <input type="text" v-model="translate.str"
                 class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  data: () => ({
    search: null,
  }),

  props: {
    keys: Array,
    locales: Array,
    locale: '',
  },

  mounted() {
    // console.log(this.keys, this.locale, this.locales)
  },

  methods: {
    update() {
      // console.log("will update", this.keys);
      Nova.request()
          .post(`/nova-vendor/translator`, {
            keys: this.keys,
            locale: this.locale
          })
          .then(response => {
            window.location = window.location.href;
          });
    },

    setLocale(locale) {
      window.location = window.location.href.replace(this.locale, locale);
    }
  }
}
</script>

<style>
/* Scoped Styles */

</style>
