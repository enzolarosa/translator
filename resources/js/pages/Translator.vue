<template>
  <div>
    <Head title="Translator"/>
    <Heading class="mb-6">{{ localize('Translator') }}</Heading>

    <Card class="flex flex-col items-center justify-center" style="min-height: 300px">
      <table>
        <thead>
        <th>{{ localize('Key') }}</th>
        <th>{{ localize('Translation') }}</th>
        </thead>
        <tbody>
        <tr v-for="(translate, key) in keys ">
          <td> {{ key }}</td>
          <td>{{ translate }}</td>
        </tr>
        </tbody>
      </table>
<!--      <SelectControl-->
<!--          :options="locales"-->
<!--          v-model:selected="locale"-->
<!--          @input="locale = $event.target.value"-->
<!--          @change="receiveStr"/>-->

<!--      <p class="dark:text-white text-lg opacity-70">-->
<!--        {{ keys }}-->
<!--      </p>-->
    </Card>
  </div>
</template>

<script>
import api from '../api';

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
    this.setupVariable();
    console.log(this.keys, this.locale, this.locales)
  },

  methods: {
    receiveStr() {
      console.log(this.locales, this.locale, this.keys)
      return api.receiveStr(this.locale)
          .then(keys => this.keys = keys);
    },

    writeStr() {
      return api.writeStr(this.locale, this.keys)
    },

    availableLocales() {
      return api.availableLocales()
          .then(locales => this.locales = locales);
    },

    currentLocale() {
      return api.currentLocale()
          .then(locale => this.locale = locale);
    },

    setupVariable() {
      console.log("ciao asd");
      // this.availableLocales();

      // this.currentLocale();
      // this.receiveStr();
    }
  }
}
</script>

<style>
/* Scoped Styles */
</style>
