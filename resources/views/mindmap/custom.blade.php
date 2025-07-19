<x-mindmap.main title="Custom Step 1" class="p-0">
    <div class="flex w-full h-screen">
        <div id="jsmind_container"
            class="relative flex-1 overflow-auto bg-[length:40px_40px] bg-[linear-gradient(to_right,rgba(0,0,0,0.1)_1px,transparent_1px),linear-gradient(to_bottom,rgba(0,0,0,0.1)_1px,transparent_1px)]">
        </div>
    </div>

    <script>
        const myDiagram = new go.Diagram("jsmind_container", {
            initialContentAlignment: go.Spot.Center,
            layout: new go.TreeLayout({
                angle: 90,
                layerSpacing: 35,
                arrangement: go.TreeLayout.ArrangementHorizontal
            }),
            "undoManager.isEnabled": true
        });
    </script>
</x-mindmap.main>
