<style>
    * {
        margin: 0;
        padding: 0;
    }

    body {
        background-color: #C8D7C7;
    }

    /*Header and zoo logo*/
    .logo-overlay {
        display: block;
        position: absolute;
        top: -180%;
        right: 0;
        bottom: 0;
        left: 1%;
        height: 100%;

    }

    header {
        display: block;
        background-image: url('../img/Header/leaves.png'), url('../Images/Header/header-bg.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        height: 100px;
        margin-top: 0px
    }

    /*Nav Bar */
    .my-light {
        background-image: url('../img/Header/nav-bg.jpg');
    }

    nav ul {
        margin-left: 30%;
    }

    /*Color customization */
    .my-text-info {
        color: teal;
        font-size: medium;
        font-weight: bold;
    }

    /*Main body of page*/
    main {
        overflow: auto;
        min-height: 70vh;
        position: auto;
        margin: auto;
    }

    /*Line above footer and around nav bar*/
    hr {
        height: 2px;
        border-width: 0;
        color: #DBA35C;
        background-color: #DBA35C;
        padding: 0 !important;
        margin: 0 !important;
    }

    /*Footer aka copyright*/
    footer {
        background-color: darkgreen;
        background-image: url('../img/birds.png');
        background-position: right;
        background-repeat: no-repeat;
        background-size: 17%;
        position: relative;
        padding-top: 15px;
        padding-bottom: 15px;
        left: 0;
        bottom: 0;
        width: 100%;
    }

    .f-top {
        max-width: 1170px;
        margin: auto;
    }

    .f-top .row {
        display: flex;
        flex-wrap: wrap;
    }

    .f-top ul {
        list-style: none;
        list-style-position: left;
    }

    .f-top .col {
        width: 22%;
        padding: 0 15px;
    }

    .f-top h4 {
        font-size: 18px;
        color: #ffffff;
        text-transform: capitalize;
        margin-bottom: 35px;
        font-weight: 500;
        position: relative;
    }

    .f-top h4::before {
        content: '';
        position: absolute;
        left: 0;
        bottom: -10px;
        background-color: #e91e63;
        height: 2px;
        box-sizing: border-box;
        width: 50px;
    }

    .f-top li:not(:last-child) {
        margin-bottom: 10px;
    }

    .f-top a {
        font-size: 16px;
        text-transform: capitalize;
        color: #ffffff;
        text-decoration: none;
        font-weight: 300;
        color: #bbbbbb;
        display: block;
        transition: all 0.3s ease;
    }

    .f-top a:hover {
        color: #ffffff;
        padding-left: 8px;
    }
</style>