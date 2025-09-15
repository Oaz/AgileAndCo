/*
Agile&Co.
Copyright (C) 2025 Olivier Azeau

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

import {mount} from 'svelte';
import {BGA} from "./BGA";
import GameBoard from "./GameBoard.svelte";
import PlayerPanel from "./PlayerPanel.svelte";
import {debuglog} from './logger';

export function initBgaIntegration(performAction, translate, host) {
    BGA.setPerformAction(performAction);
    BGA.setTranslate(translate);
    BGA.setHost(host);
}

export function createGameBoard(target, info) {
    try {
        return mount(GameBoard, {target: target, props: info,});
    } catch (error) {
        console.error('Error initializing GameBoard:', error);
    }
}

export function createPlayerPanel(target, info) {
    try {
        return mount(PlayerPanel, {target: target, props: info,});
    } catch (error) {
        console.error('Error initializing PlayerPanel:', error);
    }
}

export function debug(msg) {
    debuglog(msg);
}