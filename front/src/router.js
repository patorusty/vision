import Vue from "vue";
import Router from "vue-router";

Vue.use(Router);

export default new Router({
  mode: "history",
  base: process.env.BASE_URL,
  routes: [
    // {
    //   path: "/polizas",
    //   // component: () => import('@/views/polizas/Automotor'),
    //   children: []
    // },
    {
      path: "/",
      component: () => import("@/views/dashboard/Index"),
      children: [

        // Dashboard
        {
          name: "Automotor",
          path: "polizas/automotor",
          component: () => import("@/views/pages/polizas/Automotor")
        },
        {
          name: "OtrosRiesgos",
          path: "polizas/otrosriesgos",
          component: () => import("@/views/pages/polizas/OtrosRiesgos")
        },

        {
          name: "Companias",
          path: "administracion/companias",
          component: () => import(
            "@/views/pages/Administracion/Companias/Companias.vue"
          )
        },
        {
          name: "Organizadores",
          path: "administracion/organizadores",
          component: () =>
            import(
              "@/views/pages/Administracion/Organizadores/Organizadores.vue"
            )
        },
        {
          name: "Productores",
          path: "administracion/productores",
          component: () =>
            import("@/views/pages/Administracion/Productores/Productores.vue")
        },
        {
          name: "Usuarios",
          path: "configuracion/usuarios",
          component: () => import("@/views/pages/configuracion/Usuarios")
        },
      ]

    }
  ]
});
