<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BurgerHouse – Menu</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --teal: #0D9E8A;
            --teal-dark: #0A7A6E;
            --teal-nav: #0D9488;
            --teal-light: #E6F7F4;
            --bg: #F0F4F8;
            --white: #fff;
            --text: #1C1C2E;
            --muted: #9CA3AF;
            --border: #E8EDF2;
            --shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        /* ─── BASE ─── */
        body {
            font-family: 'Nunito', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── TOP BAR ─── */
        .topbar {
            background: var(--white);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 0 var(--border);
        }

        .ham {
            display: flex;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
        }

        .ham span {
            display: block;
            height: 2px;
            background: #555;
            border-radius: 2px;
        }

        .ham span:nth-child(1) {
            width: 22px;
        }

        .ham span:nth-child(2) {
            width: 14px;
        }

        .ham span:nth-child(3) {
            width: 22px;
        }

        .brand {
            font-weight: 900;
            font-size: 1.1rem;
            color: var(--text);
        }

        .brand span {
            color: var(--teal);
        }

        .bell {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            position: relative;
            background: var(--white);
        }

        .bell-dot {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 7px;
            height: 7px;
            background: var(--teal);
            border-radius: 50%;
            border: 1.5px solid #fff;
        }

        /* ─── GREETING ─── */
        .greeting {
            background: var(--white);
            padding: 4px 20px 18px;
        }

        .greeting h1 {
            font-size: clamp(1.4rem, 4vw, 1.7rem);
            font-weight: 900;
            line-height: 1.2;
        }

        .greeting p {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 3px;
            font-weight: 600;
        }

        .tpill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: var(--teal-light);
            color: var(--teal-dark);
            font-size: 0.71rem;
            font-weight: 800;
            padding: 5px 12px;
            border-radius: 100px;
            margin-top: 10px;
        }

        /* ─── SEARCH ─── */
        .srch {
            background: var(--white);
            padding: 0 20px 18px;
        }

        .sbar {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F0F4F8;
            border-radius: 14px;
            padding: 11px 16px;
            border: 1.5px solid transparent;
            transition: border-color .2s, background .2s;
        }

        .sbar:focus-within {
            border-color: var(--teal);
            background: #fff;
        }

        .sbar input {
            flex: 1;
            border: none;
            background: transparent;
            font-family: 'Nunito', sans-serif;
            font-size: 0.85rem;
            color: var(--text);
            outline: none;
        }

        .sbar input::placeholder {
            color: #BCC5D0;
        }

        /* ─── SECTION HEADER ─── */
        .sec {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 20px 12px;
        }

        .sec h2 {
            font-size: clamp(.95rem, 2.5vw, 1.05rem);
            font-weight: 900;
        }

        .sall {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--teal);
            cursor: pointer;
        }

        /* ─── CATEGORIES ─── */
        .cscroll {
            display: flex;
            gap: 12px;
            padding: 0 20px 4px;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .cscroll::-webkit-scrollbar {
            display: none;
        }

        .cat {
            flex-shrink: 0;
            width: 82px;
            background: var(--white);
            border: 1.5px solid var(--border);
            border-radius: 18px;
            padding: 12px 6px 26px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            position: relative;
            transition: all .22s;
            box-shadow: var(--shadow);
        }

        .cat:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 158, 138, .15);
        }

        .cat.active {
            background: var(--teal);
            border-color: var(--teal);
            box-shadow: 0 8px 24px rgba(13, 158, 138, .35);
            transform: translateY(-2px);
        }

        .cat.active .clbl {
            color: #fff;
        }

        .cimg {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #F0F4F8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            overflow: hidden;
        }

        .cat.active .cimg {
            background: rgba(255, 255, 255, .2);
        }

        .cat-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .clbl {
            font-size: 0.65rem;
            font-weight: 800;
            color: #555;
            text-align: center;
            line-height: 1.3;
        }

        .carr {
            position: absolute;
            bottom: 7px;
            right: 7px;
            width: 17px;
            height: 17px;
            background: var(--teal);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cat.active .carr {
            background: rgba(255, 255, 255, .3);
        }

        .carr svg {
            width: 8px;
            height: 8px;
            stroke: #fff;
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ─── POPULAR SCROLL ─── */
        .pscroll {
            display: flex;
            gap: 12px;
            padding: 0 20px 4px;
            overflow-x: auto;
            scrollbar-width: none;
            -webkit-overflow-scrolling: touch;
        }

        .pscroll::-webkit-scrollbar {
            display: none;
        }

        .pcard {
            flex-shrink: 0;
            width: clamp(105px, 28vw, 125px);
            background: var(--white);
            border-radius: 18px;
            overflow: hidden;
            cursor: pointer;
            transition: all .22s;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
        }

        .pcard:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, .1);
        }

        .pcard.flash {
            border-color: var(--teal);
            box-shadow: 0 0 0 2px rgba(13, 158, 138, .3);
        }

        .pimg {
            width: 100%;
            aspect-ratio: 1;
            background: #F0F4F8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            overflow: hidden;
        }

        .pimg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .pbody {
            padding: 7px 9px 11px;
        }

        .pname {
            font-size: 0.7rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .prow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 5px;
        }

        .pprice {
            font-size: 0.7rem;
            font-weight: 900;
            color: #555;
        }

        /* ─── MENU GRID ─── */
        .gwrap {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            padding: 0 16px 100px;
        }

        .gcard {
            background: var(--white);
            border-radius: 16px;
            overflow: hidden;
            cursor: pointer;
            transition: all .22s;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow);
        }

        .gcard:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(0, 0, 0, .09);
        }

        .gcard.flash {
            border-color: var(--teal);
            box-shadow: 0 0 0 2px rgba(13, 158, 138, .3);
        }

        .gcard[style*="display: none"] {
            display: none !important;
        }

        .gimg {
            width: 100%;
            aspect-ratio: 1;
            background: #F0F4F8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.9rem;
            overflow: hidden;
        }

        .gimg img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .gbody {
            padding: 6px 8px 10px;
        }

        .gname {
            font-size: clamp(.6rem, .6rem + .3vw, .7rem);
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .grow {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 4px;
        }

        .gprice {
            font-size: clamp(.6rem, .6rem + .2vw, .68rem);
            font-weight: 900;
            color: #555;
        }

        /* ─── ADD BUTTONS ─── */
        .addbtn,
        .gadd {
            background: var(--teal);
            border: none;
            border-radius: 50%;
            color: #fff;
            font-weight: 900;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform .15s;
            line-height: 1;
            flex-shrink: 0;
        }

        .addbtn {
            width: 22px;
            height: 22px;
            font-size: .95rem;
        }

        .gadd {
            width: 19px;
            height: 19px;
            font-size: .88rem;
        }

        .addbtn:hover,
        .gadd:hover {
            transform: scale(1.15);
        }

        /* ─── BOTTOM NAV ─── */
        .bnav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--teal-nav);
            border-radius: 22px 22px 0 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 13px 10px 20px;
            z-index: 200;
            box-shadow: 0 -4px 24px rgba(13, 158, 138, .25);
        }

        .ntab {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
            color: rgba(255, 255, 255, .4);
            padding: 4px 10px;
            position: relative;
        }

        .ntab.active {
            color: #fff;
        }

        .ntab svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .ntab:not(.active) svg {
            stroke: rgba(255, 255, 255, .4);
        }

        .ntab.active svg {
            stroke: #fff;
        }

        .cdot {
            position: absolute;
            top: 0;
            right: 5px;
            background: #fff;
            color: var(--teal);
            font-size: 0.5rem;
            font-weight: 900;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
        }

        .cdot.show {
            display: flex;
        }

        /* ─── OVERLAY & CART SHEET ─── */
        .ov {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 300;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .ov.show {
            opacity: 1;
            pointer-events: all;
        }

        .sheet {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-radius: 26px 26px 0 0;
            z-index: 400;
            padding: 0 20px 40px;
            transition: transform .38s cubic-bezier(.4, 0, .2, 1);
            max-height: 82vh;
            overflow-y: auto;
            transform: translateY(100%);
        }

        .sheet.show {
            transform: translateY(0);
        }

        .shandle {
            width: 38px;
            height: 4px;
            background: #E0E0E0;
            border-radius: 2px;
            margin: 14px auto 20px;
            cursor: pointer;
        }

        .stitle {
            font-size: 1.15rem;
            font-weight: 900;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sclose {
            width: 30px;
            height: 30px;
            background: #F4F6F8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: .9rem;
            color: #888;
        }

        .ecart {
            text-align: center;
            padding: 2.5rem 0;
            color: #ccc;
            font-size: 0.85rem;
            font-weight: 700;
        }

        .ci {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #F8FFFD;
            border: 1.5px solid #D4F5EF;
            border-radius: 14px;
            padding: 12px 14px;
            margin-bottom: 10px;
            gap: 8px;
        }

        .ciinfo {
            flex: 1;
            min-width: 0;
        }

        .ciname {
            font-size: 0.85rem;
            font-weight: 800;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .cisub {
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 2px;
            font-weight: 600;
        }

        .cictrl {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .cibtn {
            width: 27px;
            height: 27px;
            border-radius: 9px;
            border: none;
            background: var(--teal-light);
            color: var(--teal-dark);
            font-size: 1rem;
            font-weight: 900;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .cibtn:hover {
            background: var(--teal);
            color: #fff;
        }

        .ciqty {
            font-weight: 900;
            font-size: 0.88rem;
            min-width: 18px;
            text-align: center;
        }

        .divdr {
            border: none;
            border-top: 1.5px solid #F0F0F0;
            margin: 16px 0;
        }

        .trow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
        }

        .tlbl {
            font-weight: 700;
            color: var(--muted);
            font-size: 0.88rem;
        }

        .tval {
            font-weight: 900;
            font-size: 1.35rem;
            color: var(--teal-dark);
        }

        .pbtn {
            width: 100%;
            padding: 15px;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 16px;
            font-family: 'Nunito', sans-serif;
            font-weight: 900;
            font-size: 0.95rem;
            cursor: pointer;
            transition: opacity .2s, transform .2s;
        }

        .pbtn:hover {
            opacity: .9;
            transform: scale(1.01);
        }

        /* ─── NOTE MODAL ─── */
        .mov {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            z-index: 500;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s;
        }

        .mov.show {
            opacity: 1;
            pointer-events: all;
        }

        .mbox {
            background: #fff;
            border-radius: 26px 26px 0 0;
            padding: 20px 22px 40px;
            width: 100%;
            max-width: 600px;
            transform: translateY(100%);
            transition: transform .35s cubic-bezier(.4, 0, .2, 1);
        }

        .mov.show .mbox {
            transform: translateY(0);
        }

        .mh {
            width: 38px;
            height: 4px;
            background: #E0E0E0;
            border-radius: 2px;
            margin: 0 auto 20px;
        }

        .mt {
            font-size: 1.1rem;
            font-weight: 900;
            margin-bottom: 4px;
        }

        .ms {
            font-size: 0.78rem;
            color: var(--muted);
            font-weight: 600;
            margin-bottom: 14px;
        }

        .mta {
            width: 100%;
            background: #F4F6F8;
            border: 1.5px solid #E8EDF2;
            border-radius: 14px;
            padding: 12px;
            color: var(--text);
            font-family: 'Nunito', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            resize: none;
            outline: none;
            transition: border-color .2s;
        }

        .mta:focus {
            border-color: var(--teal);
            background: #fff;
        }

        .macts {
            display: flex;
            gap: 10px;
            margin-top: 14px;
        }

        .mcancel {
            flex: 1;
            padding: 13px;
            background: #F4F6F8;
            border: none;
            border-radius: 13px;
            color: #666;
            font-family: 'Nunito', sans-serif;
            font-weight: 700;
            font-size: 0.86rem;
            cursor: pointer;
        }

        .mconfirm {
            flex: 2;
            padding: 13px;
            background: var(--teal);
            border: none;
            border-radius: 13px;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 900;
            font-size: 0.86rem;
            cursor: pointer;
        }

        .mconfirm:hover {
            opacity: .9;
        }

        /* ─── TOAST ─── */
        .toast {
            position: fixed;
            top: 72px;
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            background: #1C1C2E;
            color: #fff;
            font-size: 0.8rem;
            font-weight: 700;
            padding: 10px 22px;
            border-radius: 100px;
            z-index: 700;
            opacity: 0;
            pointer-events: none;
            transition: all .28s;
            white-space: nowrap;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* ─── EMPTY STATE ─── */
        .no-items {
            grid-column: 1/-1;
            text-align: center;
            padding: 3rem 0;
            color: #ccc;
            font-size: 0.85rem;
            font-weight: 700;
        }

        /* RESPONSIVE BREAKPOINTS */

        /* Tablet & small desktop */
        @media(min-width:600px) {
            body {
                max-width: 600px;
                margin: 0 auto;
            }

            .gwrap {
                grid-template-columns: repeat(3, 1fr);
                padding: 0 24px 100px;
                gap: 14px;
            }

            .gimg {
                font-size: 2.5rem;
            }

            .gname {
                font-size: 0.78rem;
            }

            .gprice {
                font-size: 0.72rem;
            }

            .gadd {
                width: 22px;
                height: 22px;
                font-size: 1rem;
            }

            .pcard {
                width: 135px;
            }

            .cat {
                width: 90px;
            }

            .cimg {
                width: 55px;
                height: 55px;
                font-size: 2rem;
            }

            .greeting h1 {
                font-size: 1.85rem;
            }
        }

        @media(min-width:900px) {
            body {
                max-width: 900px;
                margin: 0 auto;
            }

            .topbar,
            .greeting,
            .srch {
                padding-left: 32px;
                padding-right: 32px;
            }

            .sec {
                padding-left: 32px;
                padding-right: 32px;
            }

            .cscroll {
                padding-left: 32px;
            }

            .pscroll {
                padding-left: 32px;
            }

            .gwrap {
                grid-template-columns: repeat(3, 1fr);
                padding: 0 32px 100px;
                gap: 16px;
            }

            .gimg {
                font-size: 3rem;
            }

            .gname {
                font-size: 0.85rem;
            }

            .gprice {
                font-size: 0.78rem;
            }

            .gadd {
                width: 24px;
                height: 24px;
                font-size: 1.1rem;
            }

            .pcard {
                width: 155px;
            }

            .cat {
                width: 96px;
                padding: 14px 8px 28px;
            }

            .cimg {
                width: 58px;
                height: 58px;
                font-size: 2.1rem;
            }

            .greeting h1 {
                font-size: 2rem;
            }

            .bnav {
                max-width: 900px;
                left: 50%;
                transform: translateX(-50%);
            }

            .sheet {
                max-width: 600px;
                left: 50%;
                transform: translateX(-50%) translateY(100%);
            }

            .sheet.show {
                transform: translateX(-50%) translateY(0);
            }
        }

        @media(min-width:1200px) {
            body {
                max-width: 1100px;
                margin: 0 auto;
            }

            .topbar,
            .greeting,
            .srch {
                padding-left: 48px;
                padding-right: 48px;
            }

            .sec {
                padding-left: 48px;
                padding-right: 48px;
            }

            .cscroll {
                padding-left: 48px;
            }

            .pscroll {
                padding-left: 48px;
            }

            .gwrap {
                grid-template-columns: repeat(3, 1fr);
                padding: 0 48px 100px;
                gap: 20px;
            }

            .gimg {
                font-size: 3.5rem;
            }

            .gname {
                font-size: 0.9rem;
            }

            .gprice {
                font-size: 0.82rem;
            }

            .gadd {
                width: 26px;
                height: 26px;
                font-size: 1.1rem;
            }

            .pcard {
                width: 170px;
            }

            .greeting h1 {
                font-size: 2.2rem;
            }

            .bnav {
                max-width: 1100px;
                left: 50%;
                transform: translateX(-50%);
            }
        }

        /* Very small phones */
        @media(max-width:360px) {
            .gwrap {
                grid-template-columns: repeat(3, 1fr);
                gap: 7px;
                padding: 0 10px 100px;
            }

            .gname {
                font-size: 0.58rem;
            }

            .gprice {
                font-size: 0.56rem;
            }

            .gadd {
                width: 16px;
                height: 16px;
                font-size: .75rem;
            }

            .cat {
                width: 72px;
            }

        }

        /* ─── BEST APPROACH TRACKING CARD ─── */
        .track-card {
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            background: #fff;
            border-radius: 20px;
            padding: 18px;
            z-index: 1000;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            display: none;
            flex-direction: column;
            gap: 12px;
            border: 1px solid rgba(13, 158, 138, 0.15);
            animation: slideDownIn 0.5s cubic-bezier(0.18, 0.89, 0.32, 1.28);
        }

        @keyframes slideDownIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .tc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tc-badge {
            background: var(--teal-light);
            color: var(--teal);
            font-size: 0.7rem;
            font-weight: 800;
            padding: 4px 10px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tc-main {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .tc-status-label {
            font-size: 1.15rem;
            font-weight: 900;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tc-time-elapsed {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--muted);
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .tc-estimate {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--teal);
            background: var(--teal-light);
            padding: 8px 12px;
            border-radius: 12px;
            text-align: center;
            margin-top: 5px;
            border: 1px solid rgba(13, 158, 138, 0.1);
        }

        .tc-progress {
            height: 6px;
            background: #f1f5f9;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }

        .tc-bar {
            height: 100%;
            background: var(--teal);
            width: 0%;
            transition: width 1s ease;
        }

        .tc-steps {
            display: flex;
            justify-content: space-between;
            margin-top: 5px;
        }

        .tc-step {
            font-size: 0.65rem;
            font-weight: 700;
            color: #cbd5e1;
        }

        .tc-step.active {
            color: var(--teal);
        }

        /* Items list in track card */
        .tc-items-list {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #eee;
            max-height: 150px;
            overflow-y: auto;
        }

        .tc-item-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            margin-bottom: 5px;
            color: var(--text);
        }

        .tc-item-name {
            font-weight: 600;
        }

        .tc-item-qty {
            color: var(--muted);
            font-weight: 700;
        }
    </style>
</head>

<body>

    {{-- ─── TOP BAR ─── --}}
    <div class="topbar">
        <div class="ham"><span></span><span></span><span></span></div>
        <div class="brand">Burger<span>House</span></div>
        <div class="bell">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                <path d="M13.73 21a2 2 0 0 1-3.46 0" />
            </svg>
            <div class="bell-dot"></div>
        </div>
    </div>

    {{-- ─── GREETING ─── --}}
    <div class="greeting">
        <h1>Hi, there 👋</h1>
        <p>What would you like to order today?</p>
        <div class="tpill">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="3">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <line x1="12" y1="3" x2="12" y2="21" />
                <line x1="3" y1="12" x2="21" y2="12" />
            </svg>
            Table #{{ $table->table_number ?? '1' }}
        </div>
    </div>

    {{-- ─── SEARCH ─── --}}
    <div class="srch">
        <div class="sbar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#BCC5D0" stroke-width="2">
                <circle cx="11" cy="11" r="8" />
                <path d="m21 21-4.35-4.35" />
            </svg>
            <input type="text" placeholder="Search menu items…" oninput="filterMenu(this.value)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0D9E8A" stroke-width="2">
                <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z" />
                <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                <line x1="12" y1="19" x2="12" y2="23" />
                <line x1="8" y1="23" x2="16" y2="23" />
            </svg>
        </div>
    </div>

    {{-- ─── CATEGORIES ─── --}}
    <div class="sec">
        <h2>Categories</h2><span class="sall">See all</span>
    </div>

    <div class="cscroll">
        <div class="cat active" onclick="filterCat('all',this)">
            <div class="cimg">🍽️</div>
            <div class="clbl">All</div>
            <div class="carr"><svg viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg></div>
        </div>
    
    <div class="cat" onclick="filterCat('burger',this)">
            <div class="cimg">🍔</div>
            <div class="clbl">Burgers</div>
            <div class="carr"><svg viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg></div>
        </div>
        <div class="cat" onclick="filterCat('chicken',this)">
            <div class="cimg">🍗</div>
            <div class="clbl">Chicken</div>
            <div class="carr"><svg viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg></div>
        </div>
        <div class="cat" onclick="filterCat('fries',this)">
            <div class="cimg">🍟</div>
            <div class="clbl">Fries</div>
            <div class="carr"><svg viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg></div>
        </div>
        <div class="cat" onclick="filterCat('drink',this)">
            <div class="cimg">🥤</div>
            <div class="clbl">Drinks</div>
            <div class="carr"><svg viewBox="0 0 24 24">
                    <polyline points="9 18 15 12 9 6" />
                </svg></div>
        </div>
    </div>

    {{-- ─── POPULAR (first 4 items) ─── --}}
    <div class="sec">
        <h2>Popular</h2><span class="sall">See all</span>
    </div>

    <div class="pscroll">
        @forelse($menuItems->take(4) as $item)
            <div class="pcard"
                onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})">
                <div class="pimg">
                    @if ($item->image)
                        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        🍔
                    @endif
                </div>
                <div class="pbody">
                    <div class="pname">{{ $item->name }}</div>
                    <div class="prow">
                        <div class="pprice">Rs. {{ number_format($item->price) }}</div>
                        <button class="addbtn"
                            onclick="event.stopPropagation();addToCart({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }})">+</button>
                    </div>
                </div>
            </div>
        @empty
            <p style="padding:0 20px;color:var(--muted);font-size:.85rem;font-weight:700;">No items available.</p>
        @endforelse
    </div>

    {{-- ─── ALL MENU ITEMS GRID (3 per row) ─── --}}
    <div class="sec">
        <h2>What's New? 🔥</h2><span class="sall">See all</span>
    </div>

    <div class="gwrap" id="menuGrid">
        @forelse($menuItems as $item)
            <div class="gcard" data-id="{{ $item->id }}" data-name="{{ strtolower($item->name) }}"
                data-desc="{{ strtolower($item->description ?? '') }}"
                data-cat="{{ strtolower(Str::slug($item->category ?? 'all')) }}"
                onclick="addToCart({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }})">
                <div class="gimg">
                    @if ($item->image)
                        <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        🍔
                    @endif
                </div>
                <div class="gbody">
                    <div class="gname">{{ $item->name }}</div>
                    <div class="grow">
                        <div class="gprice">Rs. {{ number_format($item->price) }}</div>
                        <button class="gadd"
                            onclick="event.stopPropagation();addToCart({{ $item->id }},'{{ addslashes($item->name) }}',{{ $item->price }})">+</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-items">
                <div style="font-size:2rem;margin-bottom:8px">🍽️</div>
                No menu items found.
            </div>
        @endforelse
    </div>

    </div>

    {{-- ─── TRACKING CARD ─── --}}
    <div id="trackBar" class="track-card">
        <div class="tc-header">
            <span class="tc-badge" id="orderIdLabel">Order #0</span>
            <button onclick="this.parentElement.parentElement.style.display='none'" style="background:none; border:none; color:#cbd5e1; cursor:pointer; font-size:1.2rem">&times;</button>
        </div>
        <div class="tc-main">
            <div class="tc-status-label">
                <span id="tcEmoji">🕒</span>
                <span id="statusLabel">Preparing...</span>
            </div>
            <div class="tc-time-elapsed" id="timeLabel">0m ago</div>
        </div>
        
        <div class="tc-estimate" id="tcEstimate">
            ⏳ Your food will be served in <b><span id="estimateTime">30:00</span></b>
            <!-- <div style="font-size:0.8rem; margin-top: 6px;">
                Current time: <span id="currentTime"></span>
            </div> -->
        </div>

        <div class="tc-footer">
            <div class="tc-progress">
                <div id="tcProgress" class="tc-bar"></div>
            </div>
            <div class="tc-steps">
                <span class="tc-step" id="s1">Received</span>
                <span class="tc-step" id="s2">Cooking</span>
                <span class="tc-step" id="s3">Ready</span>
            </div>
        </div>
        <div class="tc-items-list" id="tcItemsList">
            <!-- Items will be injected here -->
        </div>

        {{-- ─── Add More Items Button ─── --}}
        <div id="trackAddMoreBtn" style="display:none; padding: 10px 16px 4px;">
            <button onclick="closeTrackAndAddMore()"
                style="width:100%; background: linear-gradient(135deg, #0D9E8A, #0A7A6E); color:#fff; border:none; border-radius:12px; padding:11px 0; font-size:0.88rem; font-weight:800; cursor:pointer; letter-spacing:0.02em;">
                🍕 Add More Items to This Order
            </button>
        </div>
    </div>

    {{-- ─── BOTTOM NAV ─── --}}
    <div class="bnav">
        <div class="ntab active">
            <svg viewBox="0 0 24 24">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
        </div>
       <!-- <div class="ntab">
            <svg viewBox="0 0 24 24">
                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                <circle cx="12" cy="10" r="3" />
            </svg>
        </div> -->
        
        <div class="ntab" onclick="openCart()">
            <svg viewBox="0 0 24 24">
                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                <line x1="3" y1="6" x2="21" y2="6" />
                <path d="M16 10a4 4 0 0 1-8 0" />
            </svg>
            <div class="cdot" id="cdot">0</div>
        </div>
        <div class="ntab" onclick="openTrack()">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <div style="font-size: 8px; font-weight: 900; margin-top: -2px;">TRACK</div>
        </div>
       <!-- <div class="ntab">
            <svg viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </div> -->
    </div>

    {{-- ─── CART SHEET ─── --}}
    <div class="ov" id="ov" onclick="closeCart()"></div>

    <div class="sheet" id="sheet">
        <div class="shandle" onclick="closeCart()"></div>
        <div class="stitle">
            Your Order 🛒
            <div class="sclose" onclick="closeCart()">✕</div>
        </div>
        <div id="activeOrderBadge" style="display: none; background: #e6f7f4; color: #0d7a6e; font-size: 0.8rem; font-weight: 700; padding: 6px 12px; margin: 0 20px 10px; border-radius: 8px; text-align: center; border: 1px dashed #0d9e8a;">
            Adding to active Order #<span id="activeOrderNumText"></span>
        </div>
        <div id="combineOrderContainer" style="display: none; align-items: center; justify-content: center; gap: 8px; margin: 0 20px 10px; font-size: 0.85rem; font-weight: 700; color: #1c1c2e;">
            <input type="checkbox" id="combineOrderCheckbox" checked style="width: 16px; height: 16px; accent-color: #0D9E8A; cursor: pointer;">
            <label for="combineOrderCheckbox" style="margin: 0; cursor: pointer;">Combine with active Order #<span id="combineOrderNumText"></span></label>
        </div>
        <div id="cartItems">
            <div class="ecart">
                <div style="font-size:2rem;margin-bottom:8px">🛒</div>Cart is empty — add items!
            </div>
        </div>
        <hr class="divdr">
        <div class="trow">
            <span class="tlbl">Total</span>
            <span class="tval" id="cartTotal">Rs. 0</span>
        </div>
        <button class="pbtn" onclick="showNote()">Place Order →</button>
    </div>

    {{-- ─── NOTE MODAL ─── --}}
    <div class="mov" id="noteModal" onclick="handleMOv(event)">
        <div class="mbox">
            <div class="mh"></div>
            <div class="mt">Add a Note 📝</div>
            <p class="ms">Any special instructions for the kitchen?</p>
            <textarea class="mta" id="orderNote" rows="4" placeholder="e.g. No onions, extra sauce…"></textarea>
            <div class="macts">
                <button class="mcancel" onclick="closeNote()">Cancel</button>
                <button class="mconfirm" id="continueOrderBtn">Confirm Order ✓</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        let activeOrderNumber = {{ $activeOrderNumber ?? 'null' }};
        let cart = [];

        /* ── TOAST ── */
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 2000);
        }

        /* ── CART LOGIC ── */
        function addToCart(id, name, price) {
            const ex = cart.find(i => i.id === id);
            if (ex) ex.qty++;
            else cart.push({
                id,
                name,
                price,
                qty: 1
            });
            renderCart();
            showToast('✓ ' + name + ' added');

            document.querySelectorAll('.gcard, .pcard').forEach(c => {
                if (c.dataset.id == id) {
                    c.classList.add('flash');
                    setTimeout(() => c.classList.remove('flash'), 500);
                }
            });
        }

        function removeFromCart(id) {
            const idx = cart.findIndex(i => i.id === id);
            if (idx > -1) {
                if (cart[idx].qty > 1) cart[idx].qty--;
                else cart.splice(idx, 1);
            }
            renderCart();
        }

        function renderCart() {
            const el = document.getElementById('cartItems');
            const dot = document.getElementById('cdot');
            const badge = document.getElementById('activeOrderBadge');
            const numText = document.getElementById('activeOrderNumText');
            const btn = document.querySelector('.pbtn');

            const combineContainer = document.getElementById('combineOrderContainer');
            const combineNumText = document.getElementById('combineOrderNumText');
            const combineCheckbox = document.getElementById('combineOrderCheckbox');

            if (activeOrderNumber && cart.length) {
                combineContainer.style.display = 'flex';
                combineNumText.textContent = activeOrderNumber;

                if (combineCheckbox && combineCheckbox.checked) {
                    badge.style.display = 'block';
                    badge.style.background = '#e6f7f4';
                    badge.style.color = '#0d7a6e';
                    badge.style.borderColor = '#0d9e8a';
                    badge.innerHTML = `Adding to active Order #<span id="activeOrderNumText">${activeOrderNumber}</span>`;
                    if (btn) btn.textContent = 'Add to Active Order →';
                } else {
                    badge.style.display = 'block';
                    badge.style.background = '#eff6ff';
                    badge.style.color = '#1e40af';
                    badge.style.borderColor = '#3b82f6';
                    badge.innerHTML = 'Placing as a new separate order';
                    if (btn) btn.textContent = 'Place New Order →';
                }
            } else {
                combineContainer.style.display = 'none';
                badge.style.display = 'none';
                if (btn) btn.textContent = 'Place Order →';
            }

            let total = 0,
                qty = 0;

            if (!cart.length) {
                el.innerHTML =
                    '<div class="ecart"><div style="font-size:2rem;margin-bottom:8px">🛒</div>Cart is empty — add items!</div>';
                document.getElementById('cartTotal').textContent = 'Rs. 0';
                dot.classList.remove('show');
                return;
            }

            el.innerHTML = '';
            cart.forEach(item => {
                total += item.price * item.qty;
                qty += item.qty;
                el.innerHTML += `
      <div class="ci">
        <div class="ciinfo">
          <div class="ciname">${item.name}</div>
          <div class="cisub">Rs. ${item.price} × ${item.qty} = Rs. ${item.price * item.qty}</div>
        </div>
        <div class="cictrl">
          <button class="cibtn" onclick="removeFromCart(${item.id})">−</button>
          <span class="ciqty">${item.qty}</span>
          <button class="cibtn" onclick="addToCart(${item.id},'${item.name}',${item.price})">+</button>
        </div>
      </div>`;
            });

            document.getElementById('cartTotal').textContent = 'Rs. ' + total.toLocaleString();
            dot.textContent = qty;
            dot.classList.add('show');
        }

        /* ── CART SHEET ── */
        function openCart() {
            document.getElementById('ov').classList.add('show');
            document.getElementById('sheet').classList.add('show');
        }

        function closeCart() {
            document.getElementById('ov').classList.remove('show');
            document.getElementById('sheet').classList.remove('show');
        }

        /* ── NOTE MODAL ── */
        function showNote() {
            if (!cart.length) {
                showToast('Cart is empty!');
                return;
            }
            closeCart();
            document.getElementById('noteModal').classList.add('show');
        }

        function closeNote() {
            document.getElementById('noteModal').classList.remove('show');
        }

        function handleMOv(e) {
            if (e.target === e.currentTarget) closeNote();
        }

        /* ── COMBINE ORDER TOGGLE ── */
        document.getElementById('combineOrderCheckbox').addEventListener('change', () => {
            renderCart();
        });

        /* ── PLACE ORDER ── */
        document.getElementById('continueOrderBtn').addEventListener('click', () => {
            const note = document.getElementById('orderNote').value || 'No note';
            const combineCheckbox = document.getElementById('combineOrderCheckbox');
            const combine = combineCheckbox ? combineCheckbox.checked : false;

            const orderData = {
                table_id: {{ $table->id ?? 1 }},
                order_number: combine ? activeOrderNumber : null,
                items: cart.map(i => ({
                    item_id: i.id,
                    quantity: i.qty,
                    total: i.price * i.qty
                })),
                note,
                _token: '{{ csrf_token() }}'
            };

            fetch("{{ route('orders.store') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast(combine ? '✅ Order updated! #' + data.order_number : '✅ Order placed! #' + data.order_number);
                        activeOrderNumber = data.order_number;
                        cart = [];
                        renderCart();
                        closeNote();
                        document.getElementById('orderNote').value = '';
                        initTracking();
                        openTrack();
                    } else {
                        showToast('❌ Error placing order');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showToast('❌ Something went wrong!');
                });
        });

        /* ── CATEGORY FILTER ── */
        function filterCat(cat, el) {
            document.querySelectorAll('.cat').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
            document.querySelectorAll('.gcard').forEach(card => {
                card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
            });
        }

        /* ── SEARCH FILTER ── */
        function filterMenu(v) {
            v = v.toLowerCase();
            document.querySelectorAll('.gcard').forEach(card => {
                const match = card.dataset.name.includes(v) || card.dataset.desc.includes(v);
                card.style.display = (!v || match) ? '' : 'none';
            });
        }

    </script>

    <script>
        /* ── TRACKING SYSTEM (Best Approach) ── */
        let orderPlacedAt = null;
        let trackingInterval = null;

        const STATUS_CONFIG = {
            'pending': { text: 'Order Received', emoji: '📩', progress: '33%', step: 1 },
            'preparing': { text: 'Chef is cooking', emoji: '👨‍🍳', progress: '66%', step: 2 },
            'served': { text: 'Food is Ready!', emoji: '🍽️', progress: '100%', step: 3 },
            'cancelled': { text: 'Order Cancelled', emoji: '❌', progress: '100%', step: 0 }
        };

        let activeEstimateOrder = null;
        let estimateEndTime = null;
        let estimateInterval = null;

        function initTracking() {
            if (trackingInterval) clearInterval(trackingInterval);
            pollStatus(false); // Silent poll
            trackingInterval = setInterval(() => pollStatus(false), 15000);
        }

        function openTrack() {
            pollStatus(true); // Show card
        }

        function pollStatus(shouldShow) {
            fetch("{{ route('users.orderStatus', $table->qr_token) }}")
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (data.status === 'pending' || data.status === 'preparing') {
                            activeOrderNumber = data.order_number;
                        } else {
                            activeOrderNumber = null;
                        }
                        renderCart();

                        const config = STATUS_CONFIG[data.status] || STATUS_CONFIG['pending'];
                        const bar = document.getElementById('trackBar');
                        
                        if(shouldShow) bar.style.display = 'flex';
                        
                        document.getElementById('statusLabel').textContent = config.text;
                        document.getElementById('tcEmoji').textContent = config.emoji;
                        document.getElementById('tcProgress').style.width = config.progress;
                        document.getElementById('orderIdLabel').textContent = 'Order #' + data.order_number;

                        // Show/Hide Estimate
                        const estEl = document.getElementById('tcEstimate');
                        if (data.status === 'served' || data.status === 'cancelled') {
                            estEl.style.display = 'none';
                            if (estimateInterval) {
                                clearInterval(estimateInterval);
                                estimateInterval = null;
                            }
                            activeEstimateOrder = null;
                        } else {
                            estEl.style.display = 'block';
                            startEstimateCountdown(30, data.order_number);
                        }

                        // Show/Hide Add More Items button
                        const addMoreBtn = document.getElementById('trackAddMoreBtn');
                        if (addMoreBtn) {
                            addMoreBtn.style.display = (data.status === 'pending' || data.status === 'preparing') ? 'block' : 'none';
                        }

                        // Render Items
                        const listEl = document.getElementById('tcItemsList');
                        listEl.innerHTML = '';
                        if(data.items) {
                            data.items.forEach(it => {
                                listEl.innerHTML += `
                                    <div class="tc-item-row">
                                        <span class="tc-item-name">${it.name}</span>
                                        <span class="tc-item-qty">x${it.qty}</span>
                                    </div>
                                `;
                            });
                        }

                        // Update Steps
                        document.querySelectorAll('.tc-step').forEach(s => s.classList.remove('active'));
                        for(let i=1; i<=config.step; i++) {
                            const el = document.getElementById('s' + i);
                            if(el) el.classList.add('active');
                        }

                        orderPlacedAt = new Date(data.created_at);
                        updateLiveTime(data.server_time);
                    } else if(shouldShow) {
                        showToast('No active order found!');
                    }
                });
        }

        /* ── CLOSE TRACK & ADD MORE ITEMS ── */
        function closeTrackAndAddMore() {
            document.getElementById('trackBar').style.display = 'none';
            openCart();
            showToast('🍕 Browse menu and add items to your order!');
        }

        function updateLiveTime(serverTimeStr) {
            if (!orderPlacedAt) return;
            const now = serverTimeStr ? new Date(serverTimeStr) : new Date();
            const diffMs = now - orderPlacedAt;
            const diffMins = Math.floor(diffMs / 60000);
            document.getElementById('timeLabel').textContent = diffMins < 1 ? 'Just now' : diffMins + 'm ago';
        }

        function startEstimateCountdown(minutes = 30, orderNumber = null) {
            if (activeEstimateOrder === orderNumber && estimateInterval) {
                return; // keep existing countdown for same active order
            }

            activeEstimateOrder = orderNumber;
            estimateEndTime = new Date(Date.now() + minutes * 60000);

            if (estimateInterval) clearInterval(estimateInterval);
            estimateInterval = setInterval(() => {
                updateEstimateCountdown();
                updateCurrentTime();
            }, 1000);

            updateEstimateCountdown();
            updateCurrentTime();
        }

        function updateEstimateCountdown() {
            const el = document.getElementById('estimateTime');
            const container = document.getElementById('tcEstimate');
            if (!el || !container || !estimateEndTime) return;

            const now = new Date();
            const diff = estimateEndTime - now;
            if (diff <= 0) {
                el.textContent = '00:00';
                container.innerHTML = '⏳ Your food is arriving soon';
                clearInterval(estimateInterval);
                estimateInterval = null;
                return;
            }

            const mins = Math.floor(diff / 60000);
            const secs = Math.floor((diff % 60000) / 1000);
            el.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
        }

        function updateCurrentTime() {
            const now = new Date();
            const currentTimeEl = document.getElementById('currentTime');
            if (!currentTimeEl) return;
            currentTimeEl.textContent = now.toLocaleTimeString();
        }

        initTracking();
        setInterval(() => {
            updateLiveTime();
            updateCurrentTime();
        }, 1000);
    </script>
</body>

</html>
