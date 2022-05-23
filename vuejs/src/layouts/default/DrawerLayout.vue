<template>
  <v-navigation-drawer permanent app>
    <v-list>
      <v-list-item class="px-2">
        <v-list-item-avatar>
          <v-img src="https://randomuser.me/api/portraits/women/85.jpg"></v-img>
        </v-list-item-avatar>
      </v-list-item>

      <v-list-item link>
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            {{username || 'Username'}}
          </v-list-item-title>
          <v-list-item-subtitle v-on:click="logout">log out</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <v-divider></v-divider>

    <v-list
        dense
        nav
    >
      <v-list-item
          v-for="item in items"
          :key="item.title"
          link
      >
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>

        <v-list-item-content>
          <v-list-item-title>{{ item.title }}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
export default {
  data() {
    return {
      items: [
        {title: 'Documents', icon: 'mdi-view-dashboard'},
        {title: 'Users', icon: 'mdi-image'},
      ],
    }
  }
}
</script>