<template>
    <v-container fluid grid-list-md>
        <v-layout>
            <v-flex xs8>
                <v-card>
                    <v-toolbar dark card color="primary">
                        <v-toolbar-title>Test Cases</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-btn flat>Upload<v-icon right>cloud_upload</v-icon></v-btn>
                    </v-toolbar>
                    <v-card-title>
                        
                    </v-card-title>
                </v-card>
            </v-flex>
            <v-flex xs4>
                <v-card>
                    <v-toolbar dark card color="primary">
                        <v-toolbar-title>Templates</v-toolbar-title>
                    </v-toolbar>
                    <v-card-title>
                        <v-layout align-center>
                            <v-btn color="secondary lighten-3">Download XLS Template<v-icon right>cloud_download</v-icon></v-btn>
                        </v-layout>
                    </v-card-title>
                </v-card>
            </v-flex>
        </v-layout>
    </v-container>
</template>
