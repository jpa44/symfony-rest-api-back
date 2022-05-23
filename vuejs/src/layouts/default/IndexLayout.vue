<template>
  <v-app>
    <default-drawer />

    <default-view />
  </v-app>
</template>

<script>
export default {
  name: 'DefaultLayout',

  components: {
    DefaultDrawer: () => import(
        /* webpackChunkName: "default-drawer" */
        './DrawerLayout'
        ),
    DefaultView: () => import(
        /* webpackChunkName: "default-view" */
        './ViewLayout'
        ),
  },
}
</script>
