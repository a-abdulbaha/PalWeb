<template>
    <div class="loading-spinner">
        <svg id="emoji" viewBox="8 0 56 72" xmlns="http://www.w3.org/2000/svg">
            <g id="color">
                <path fill="#fff"
                      d="m35.498,37.967h1.0165c7.6245,0,13.8146,6.1901,13.8146,13.8146v6.4581h-28.3282v-6.7757c0-7.4492,6.0478-13.4971,13.4971-13.4971Z"/>
                <path transform="translate(71.98 47.7203) rotate(180)" fill="#fff"
                      d="m35.323,13.7238h1.0165c7.6245,0,13.8146,6.1901,13.8146,13.8146v6.4581h-28.3282v-6.7757c0-7.4492,6.0478-13.4971,13.4971-13.4971Z"/>
                <path fill="#fff"
                      d="m39.3125,33.6813s-2.8222,2.0175-.1707,4.5474c0,0-2.0336-.5557-6.3872.0707,0,0,2.7562-2.0985-.094-4.7294l6.6519.1114Z"/>
                <path class="color-accent-light"
                      d="m40.2139,31.1055c.0039-.002.0068-.002.0107-.002.0049-.0019.0088-.0039.0137-.0058,5.6543-1.4844,7.7617-7.7901,7.7617-11.4161v-1.6816h-24v1.6816c0,3.626,2.1074,9.9317,7.7617,11.4161.0049.0019.0088.0039.0137.0058.0039,0,.0068,0,.0107.002,1.792.4863,3.0996,1.5361,3.7491,2.8945h.9296c.6495-1.3584,1.9571-2.4082,3.7491-2.8945Z"/>
                <path class="color-accent-light" d="m47,55c-6.0742,0-11-4.9258-11-11,0,6.0742-4.9258,11-11,11h-1v4h24v-4h-1Z"/>
                <path class="color-dark-primary"
                      d="m55,11c0,1.1001-.9004,2-2,2H19c-1.0996,0-2-.8999-2-2v-1c0-1.1001.9004-2,2-2h34c1.0996,0,2,.8999,2,2v1Z"/>
                <path class="color-dark-primary"
                      d="m55,62c0,1.0996-.9004,2-2,2H19c-1.0996,0-2-.9004-2-2v-1c0-1.0996.9004-2,2-2h34c1.0996,0,2,.9004,2,2v1Z"/>
            </g>
            <g id="line">
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m31,34c-7.2725-1.9092-10-9.5454-10-14.3184v-6.6816"/>
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m21,59v-6.6816c0-4.7725,2.7275-12.4092,10-14.3184"/>
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m31,38c1-.2715,2-.8945,2-2,0-1.1045-1-1.7285-2-2"/>
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m41,34c7.2725-1.9092,10-9.5454,10-14.3184v-6.6816"/>
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m51,59v-6.6816c0-4.7725-2.7275-12.4092-10-14.3184"/>
                <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m41,38c-1-.2715-2-.8945-2-2,0-1.1045,1-1.7285,2-2"/>
                <path class="color-accent-medium" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m25,55c6.0742,0,11-4.9258,11-11,0,6.0742,4.9258,11,11,11"/>
                <path class="color-dark-secondary" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m55,11c0,1.1001-.9004,2-2,2H19c-1.0996,0-2-.8999-2-2v-1c0-1.1001.9004-2,2-2h34c1.0996,0,2,.8999,2,2v1Z"/>
                <line class="color-accent-medium" x1="47" x2="25" y1="18" y2="18" stroke-linecap="round"
                      stroke-linejoin="round" stroke-width="2"/>
                <path class="color-dark-secondary" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="m55,62c0,1.0996-.9004,2-2,2H19c-1.0996,0-2-.9004-2-2v-1c0-1.0996.9004-2,2-2h34c1.0996,0,2,.9004,2,2v1Z"/>
                <line x1="36" x2="36" y1="39" y2="44" stroke-linecap="round"
                      stroke-linejoin="round" stroke-width="2"/>
            </g>
        </svg>
    </div>
</template>
<style scoped>
.color-accent-light {
    fill: var(--color-accent-light);
}
.color-accent-medium {
    fill: none;
    stroke: var(--color-accent-medium);
}
.color-dark-primary {
    fill: var(--color-dark-primary);
}
.color-dark-secondary {
    fill: none;
    stroke: var(--color-dark-secondary);
}
</style>
