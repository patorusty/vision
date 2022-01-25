module.exports = {
  devServer: {
    disableHostCheck: true,
    host: 'app.vision.test'
  },

  transpileDependencies: ['vue-world-map', 'vuetify'],

  pluginOptions: {
    i18n: {
      locale: 'en',
      fallbackLocale: 'en',
      localeDir: 'locales',
      enableInSFC: false,
    },
  }
}
