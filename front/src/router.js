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
      meta: { authOnly: true },
      redirect: "home",
      children: [
        // Dashboard
        {
          name: "Home",
          path: "home",
          component: () => import("@/views/pages/Home.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Polizas",
          path: "polizas",
          component: () => import("@/views/pages/Polizas/Polizas.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Crear Poliza",
          path: "polizas/automotor/create",
          component: () => import("@/views/pages/Polizas/CreatePoliza.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Editar Poliza",
          path: "polizas/automotor/:id",
          component: () => import("@/views/pages/Polizas/EditPoliza.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Pendientes",
          path: "pendientes",
          component: () => import("@/views/pages/Pendientes/Pendientes.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Siniestros",
          path: "siniestros",
          component: () => import("@/views/pages/Siniestros/Siniestros.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Clientes",
          path: "clientes",
          component: () => import("@/views/pages/Clientes/Clientes.vue"),
          meta: { authOnly: true }
        },
        {
          name: "Editar Cliente",
          path: "clientes/:id",
          component: () => import("@/views/pages/Clientes/EditCliente.vue"),
          meta: { authOnly: true }
        },
        {
          path: "/administracion",
          component: { render: h => h("router-view") },
          meta: { authOnly: true },
          children: [
            {
              name: "Compañías",
              path: "companias",
              component: () =>
                import("@/views/pages/Administracion/Companias/Companias.vue"),
              meta: { authOnly: true }
            },
            {
              name: "Editar Compañía",
              path: "companias/:nombre",
              component: () =>
                import(
                  "@/views/pages/Administracion/Companias/EditCompania.vue"
                ),
              meta: { authOnly: true }
            },
            {
              name: "Organizadores",
              path: "organizadores",
              component: () =>
                import(
                  "@/views/pages/Administracion/Organizadores/Organizadores.vue"
                ),
              meta: { authOnly: true }
            },
            {
              name: "Productores",
              path: "productores",
              component: () =>
                import(
                  "@/views/pages/Administracion/Productores/Productores.vue"
                ),
              meta: { authOnly: true }
            },
            {
              name: "Marca/Modelo/Version",
              path: "mmv",
              component: () =>
                import("@/views/pages/Administracion/MMV/MMV.vue"),
              meta: { authOnly: true }
            }
          ]
        },
        {
          path: "/configuracion",
          component: { render: h => h("router-view") },
          meta: { authOnly: true },
          children: [
            {
              name: "Usuarios",
              path: "usuarios",
              component: () =>
                import("@/views/pages/Configuracion/Usuarios.vue"),
              meta: { authOnly: true }
            }
          ]
        }
      ]
    },
    {
      path: "/",
      component: () => import("@/views/pages/Index"),
      children: [
        {
          name: "Login",
          path: "login",
          component: () => import("@/views/pages/Login"),
          meta: { guestOnly: true }
        }
        // {
        //   name: "Registro",
        //   path: "registro",
        //   component: () => import("@/views/pages/Registro"),
        //   meta: { guestOnly: true },
        // },
      ]
    }
    // {
    //   path: "*",
    //   component: () => import("@/views/pages/Index"),
    //   children: [
    //     {
    //       name: "404 Error",
    //       path: "",
    //       component: () => import("@/views/pages/Error"),
    //     },
    //   ],
    // },
  ]
});
