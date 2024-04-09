<template>
        <!-- Textual stats that should be under the table, could
    be put at an absolute position -->
    <div class="textual-stats-wrapper">
      <p class="textual-stats">Tarif total: {{ totalFees }}</p>
      <p class="textual-stats">Tarif minimal: {{ minimalFees }}</p>
      <p class="textual-stats">Tarif maximal: {{  maximalFees  }}</p>
    </div>
</template>

<script>
export default {
    name: 'TextualStats',
}
</script>

<style>
</style>