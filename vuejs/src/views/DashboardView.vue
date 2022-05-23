<template>
    <v-container
        id="dashboard-view"
        fluid
        tag="section"
    >
      <v-row>
        <v-col cols="12">
          <v-row>
            <material-card
                color="orange"
                full-header
            >

              <v-card-text>
                <v-data-table
                    :headers="headers"
                    :items="items"
                />
              </v-card-text>
            </material-card>
          </v-row>
        </v-col>
      </v-row>
    </v-container>
</template>

<script>
import MaterialCard from "@/components/MaterialCard";
export default {
  name: "DashboardView",
  data () {
    return {
      username:this.$route.params.username
    }
  },
  components: {
    MaterialCard,
  },
  methods: {
    logout() {
      this.$router.replace({name:'login'})
    }
  }
}
</script>