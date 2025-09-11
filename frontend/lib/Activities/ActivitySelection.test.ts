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

import { describe, it, expect, beforeEach, vi } from 'vitest';
import { ActivitySelection } from './ActivitySelection';
import { BGA } from '../BGA';
import { Text } from '../texts';

// Mock the translation function and BGA
vi.mock('../texts', () => ({
    _: vi.fn((text) => text),
    Text: {
        CONFIRM_ACTIVITY_SELECTION: 'confirm_activity',
        CANNOT_ACTIVITY_SELECTION: 'cannot_activity'
    }
}));

vi.mock('../BGA', () => ({
    BGA: {
        performAction: vi.fn()
    }
}));

describe('ActivitySelection', () => {
    let mockData: {
        selection: boolean;
        activities: [number, boolean, boolean][];
    };

    beforeEach(() => {
        // Reset all mocks before each test
        vi.clearAllMocks();

        // Setup mock data
        mockData = {
            selection: true,
            activities: [
                [1, false, true],  // [id, frozen, selected]
                [2, true, false],
                [3, false, false]
            ]
        };
    });

    describe('constructor', () => {
        it('should initialize with correct selection states', () => {
            const activity = new ActivitySelection(mockData);
            expect(activity.selection).toEqual([true, false, false]);
        });

        it('should set correct interaction states when enabled', () => {
            const activity = new ActivitySelection(mockData);
            expect(activity.interaction).toEqual(['ACTIVE', 'FROZEN', 'ACTIVE']);
        });

        it('should set all interactions to FROZEN when disabled', () => {
            mockData.selection = false;
            const activity = new ActivitySelection(mockData);
            expect(activity.interaction).toEqual(['FROZEN', 'FROZEN', 'FROZEN']);
        });
    });

    describe('selected getter', () => {
        it('should return only selected activities', () => {
            const activity = new ActivitySelection(mockData);
            expect(activity.selected).toEqual([[1, false, true]]);
        });

        it('should return empty array when no activities selected', () => {
            mockData.activities = mockData.activities.map(act => [act[0], act[1], false]);
            const activity = new ActivitySelection(mockData);
            expect(activity.selected).toEqual([]);
        });
    });

    describe('can_act getter', () => {
        it('should return true when exactly one activity is selected', () => {
            const activity = new ActivitySelection(mockData);
            expect(activity.can_act).toBe(true);
        });

        it('should return false when no activities are selected', () => {
            mockData.activities = mockData.activities.map(act => [act[0], act[1], false]);
            const activity = new ActivitySelection(mockData);
            expect(activity.can_act).toBe(false);
        });

        it('should return false when multiple activities are selected', () => {
            mockData.activities[1][2] = true; // Select second activity
            const activity = new ActivitySelection(mockData);
            expect(activity.can_act).toBe(false);
        });
    });

    describe('action_text getter', () => {
        it('should return confirmation text when one activity is selected', () => {
            const activity = new ActivitySelection(mockData);
            expect(activity.action_text).toBe('confirm_activity');
        });

        it('should return cannot select text when no activity is selected', () => {
            mockData.activities = mockData.activities.map(act => [act[0], act[1], false]);
            const activity = new ActivitySelection(mockData);
            expect(activity.action_text).toBe('cannot_activity');
        });
    });

    describe('do_act method', () => {
        it('should call BGA.performAction with correct parameters', () => {
            const activity = new ActivitySelection(mockData);
            activity.do_act();
            expect(BGA.performAction).toHaveBeenCalledWith('actChooseActivity', {
                activity_id: 1
            });
        });
    });
});