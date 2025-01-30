<script>
    import {_, Text} from '../texts';
    export let cost;
    export let score;
</script>

<style>
    .cost {
        width: 20mm;
        height: 11mm;
        position: absolute;
        top: 68mm;
        left: 5mm;
    }

    .score {
        width: 20mm;
        height: 11mm;
        position: absolute;
        top: 68mm;
        left: 28mm;
    }

    .label {
        font-family: "Arial", serif;
        font-size: 7pt;
        position: absolute;
        top: 11mm;
        left: 3mm;
        color: white;
        transform-origin: 0 0;
        transform: rotate(-90deg);
    }

    .value {
        font-family: "Arial", serif;
        font-size: 26pt;
        position: absolute;
        top: 2.5mm;
        left: 7mm;
        color: white;
    }
</style>

<div>
    <div class="cost">
        <div class="label">{_(Text.COST)}</div>
        <div class="value">{cost}</div>
    </div>
    <div class="score">
        <div class="label">{_(Text.SCORE)}</div>
        <div class="value">{score}</div>
    </div>
</div>