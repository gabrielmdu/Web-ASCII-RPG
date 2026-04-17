<script lang="ts" setup>
import type { Scene } from '@/common/types';
import { computed } from 'vue';
import Header from './Header.vue';

const { scene, gameSettings } = defineProps<{
  scene: Scene,
  gameSettings: Record<string, unknown>;
}>();

/** The width of the scene - calculated from the lengthier line in the scene's image */
const sceneWidth = computed(() => {
  const image = scene.media.image[0];
  const lineLengths = image!.split('\n').map(line => line.length);

  return Math.max(...lineLengths);
});

const mergedSettings = computed(() => {
  return {
    ...gameSettings,
    ...scene.settings
  };
});

</script>

<template>
     <div class="container flex justify-center">
        <div class="wrapper flex justify-center leading-[0.7]">
            <pre id="ascii-art"><Header :scene-width="sceneWidth" :title="scene.title" :settings="mergedSettings" />
 ----------------------------------------------------------
|                     The Pot of Gold                      |
 ----------------------------------------------------------
|                                                          |
|                   ..&&&&&&&&&&&&&&&&&&..                 |
|                .&&&&&&&&&&&&&&&&&&&&&&&&&&.              |
|             .&&############################&&.           |
|           .&&##%%%%%%%%%%%%%%%%%%%%%%%%%%%%##&&.         |
|         .&&##%%%$$$$$$$$$$$$$$$$$$$$$$$$$$%%%##&&.       |
|       .&&##%%$$@@@@@@@@@@@@@@@@@@@@@@@@@@@$$%%%##&&.     |
|      &&&##%%$$@@**************************@@$$%%##&&&    |
|     &&&##%%$$@@****                    ****@@$$%%##&&&   |
|    &&&##%%$$@@**                          **@@$$%%##&&&  |
|   &&&##%%$$@@**                            **@@$$%%##&&& |
|   &&##%%$$@@**          H A P P Y           **@@$$%%##&& |
|  _&&##%%$$@@**_                             **@@$$%%##&& |
| |   G          |  S T .  P A T R I C K ' S  **@@$$%%##&& |
|  |   O  L     |                             **@@$$%%##&& |
|  |    O  U    |           D A Y !           **@@$$%%##&& |
| /      D  C    \                            **@@$$%%##&& |
| |          K   |                            **@@$$%%##&& |
| _\____________/_____________________________**@@$$%%##&& |
|                                                          |
 ----------------------------------------------------------
| <a href="/">1) Fly away</a>                                              |
| <a href="/">2) Stay where you are</a>                                    |
 ----------------------------------------------------------
            </pre>
        </div>
    </div>
</template>

<style scoped>
.container {
  width: 100%; /* Never exceed window width */
  max-width: 100vw; /* Explicitly cap at viewport */
  min-width: 300px;
  /*aspect-ratio: 16 / 9;*/
  
  border: 1px solid #333;
  box-sizing: border-box;
}

.wrapper {
  container-type: inline-size;
  width: 100%; /* Never exceed window width */
  max-width: 800px; /* Explicitly cap at viewport */
  min-width: 300px; /* Your minimum size */
  /*aspect-ratio: 16 / 9;*/
    /*max-width: 800px;*/
}

pre {
  font-family: var(--warpg-font);
  font-style: normal;
  font-size: 4cqw;
  white-space: pre;
  display: inline-block;
  margin: 0;
}
</style>
