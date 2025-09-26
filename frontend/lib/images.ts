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

function getAsBase64(content:string) {
    if (content.startsWith('data:image/svg+xml;base64,'))
        return content;
    if (!content.startsWith('data:image/svg+xml,'))
        return content;
    const prefix = 'data:image/svg+xml,';
    const svgContent = decodeURIComponent(content.substring(prefix.length));
    const base64Content = btoa(svgContent);
    return 'data:image/svg+xml;base64,' + base64Content;
}

export function addImageinRootCss(name:string, content:string) {
    const root = document.documentElement;
    if (root.style.getPropertyValue(name))
        return;
    let url = getAsBase64(content);
    root.style.setProperty(name, `url(${url})`);
}