import { Activity, useEffect, useRef, useState } from "react";
import GridLayout, { noCompactor } from "react-grid-layout";
import "react-grid-layout/css/styles.css";
import "react-resizable/css/styles.css";
import { SearchBar } from ".//SearchBar";
import html2canvas from "html2canvas-pro";
import {
    Save,
    Plus,
    Map as MapIcon,
    RotateCw,
    Info,
    LayoutGrid,
    Layers,
    Moon,
    Sun,
} from "lucide-react";

const cekIsTableLayoutChanged = (original, edited) => {
    if (original?.length !== edited?.length) return true;
    const map = new Map(edited?.map((t) => [t.tableNumber, t]));
    return original?.some((orig) => {
        const curr = map.get(orig.tableNumber);
        if (!curr) return true;
        return (
            Number(orig.positionX) !== Number(curr.positionX) ||
            Number(orig.positionY) !== Number(curr.positionY) ||
            orig.facing !== curr.facing ||
            orig.vertical !== curr.vertical
        );
    });
};

export default function DragLayout({
    table,
    onClick,
    // baseTable,
    theme = "dark", // Menambahkan prop theme
    viewMode,
    onCLick,
}) {
    const mapRef = useRef();
    const [newTablesData, setNewTablesData] = useState(table);
    const isDark = theme === "dark";
    const [drag, setDrag] = useState(false);
    const [editMode, setEditMode] = useState("information");
    const [isTableLayoutChanged, setIsTableLayoutChanged] = useState(false);

    const editModeList = [
        { name: "information", placeholder: "Info", icon: <Info size={14} /> },
        { name: "type", placeholder: "Model", icon: <Layers size={14} /> },
        { name: "rotate", placeholder: "Rotasi", icon: <RotateCw size={14} /> },
    ];

    const printTableMap = async () => {
        const mapElement = mapRef.current;

        if (!mapElement) return;

        const originalOverflow = mapElement.style.overflow;
        const originalHeight = mapElement.style.height;

        mapElement.style.overflow = "visible";
        mapElement.style.height = "auto";

        // tunggu repaint
        await new Promise((resolve) => setTimeout(resolve, 300));

        const canvas = await html2canvas(mapElement, {
            scale: 2,
            useCORS: true,
            backgroundColor: isDark ? "#020617" : "#ffffff",

            width: mapElement.scrollWidth,
            height: mapElement.scrollHeight,

            windowWidth: mapElement.scrollWidth,
            windowHeight: mapElement.scrollHeight,
        });

        // restore style
        mapElement.style.overflow = originalOverflow;
        mapElement.style.height = originalHeight;

        // convert png
        const image = canvas.toDataURL("image/png");

        // download
        const link = document.createElement("a");

        link.href = image;

        link.download = `restaurant-map-${Date.now()}.png`;

        document.body.appendChild(link);

        link.click();

        document.body.removeChild(link);
    };

    useEffect(() => {
        window.printTableMap = printTableMap;

        return () => {
            delete window.printTableMap;
        };
    }, [printTableMap]);

    useEffect(() => {
        const changed = cekIsTableLayoutChanged(table, newTablesData);
        setIsTableLayoutChanged(changed);
        window.isTableLayoutChanged = changed;
        window.newTablesData = newTablesData;

        // Trigger custom event agar Blade tahu ada perubahan data terbaru
        const event = new CustomEvent("reactLayoutUpdated", {
            detail: { isTableLayoutChanged, newTablesData },
        });
        window.dispatchEvent(event);
    }, [newTablesData, table]);

    function scrollToTable(table) {
        if (!mapRef.current || !table.positionX || !table.positionY) return;
        const ROW_HEIGHT = 150;
        const MARGIN_X = 10;
        const MARGIN_Y = 10;
        const containerWidth = window.innerWidth * 0.975 * 5;
        const colWidth = (containerWidth - MARGIN_X * 61) / 60;

        const x = table.positionX * colWidth + MARGIN_X * (table.positionX + 1);
        const y =
            table.positionY * ROW_HEIGHT + MARGIN_Y * (table.positionY + 1);

        mapRef.current.scrollTo({
            left: x - mapRef.current.clientWidth / 2,
            top: y - mapRef.current.clientHeight / 2,
            behavior: "smooth",
        });
    }

    // LOGIKA UKURAN ASLI (TIDAK DIUBAH)
    const [layout, setLayout] = useState(
        table.map((data, idx) => ({
            i: String(idx),
            x: Math.ceil(data.positionX),
            y: Math.ceil(data.positionY),
            w: Math.ceil(
                data.vertical
                    ? 2
                    : !(data.capacity > 4)
                      ? !data.facing
                          ? 2
                          : 2
                      : (data.capacity / 4) * 1 + 2,
            ),
            h: Math.ceil(
                data.vertical
                    ? !(data.capacity > 4)
                        ? !data.facing
                            ? 2
                            : 2
                        : (data.capacity / 4) * 1 + 2
                    : 2,
            ),
        })),
    );

    // useEffect(() => {
    //   if (baseTable)
    //     setLayout(() =>
    //       baseTable.map((data, idx: number) => ({
    //         i: String(idx),
    //         x: data.positionX,
    //         y: data.positionY,
    //         w: Math.ceil(
    //           data.vertical
    //             ? 1.75
    //             : !(data.capacity > 4)
    //               ? 2
    //               : (data.capacity / 4) * 1 + 6 / data.capacity
    //         ),
    //         h: Math.ceil(data.vertical ? (!(data.capacity > 4) ? 1 : (data.capacity / 4) * 0.8) : 1),
    //       }))
    //     )
    // }, [baseTable])

    useEffect(() => {
        setLayout((prev) =>
            newTablesData.map((data, idx) => ({
                ...prev[idx],
                w: Math.ceil(
                    !(data.capacity > 4)
                        ? !data.facing
                            ? 2
                            : 2
                        : data.vertical
                          ? 2
                          : (data.capacity / 4) * 1 + 6 / data.capacity,
                ),
                h: Math.ceil(
                    !(data.capacity > 4)
                        ? !data.facing
                            ? 2
                            : 2
                        : data.vertical
                          ? (data.capacity / 4) * 1 + 6 / data.capacity
                          : 2,
                ),
            })),
        );
    }, [newTablesData]);

    useEffect(() => {
        console.log("layout baru", layout);
    }, [layout]);

    const ChairComponent = ({ chairPosition, idx, many }) => (
        <div
            className={`flex w-12 h-12 transition-all duration-300 ${
                many
                    ? `rounded${chairPosition[idx]?.split("rounded")?.[1]}`
                    : `absolute ${chairPosition[idx]}`
            }`}
            key={idx}
        >
            <div
                className={`rounded-full flex-1 shadow-inner border ${
                    isDark
                        ? "bg-slate-700 border-white/10"
                        : "bg-slate-300 border-black/5"
                }`}
            ></div>
        </div>
    );

    const TableComponent = ({
        capacity,
        tableNumber,
        berhadapan = false,
        onClick,
        vertical,
        session,
    }) => {
        let chairPosition = [
            "left-0 -translate-x-3/4 rounded-l-full",
            "right-0 translate-x-3/4 rounded-r-full",
            "top-0 -translate-y-3/4 rounded-t-full",
            "bottom-0 translate-y-3/4 rounded-b-full",
        ];

        if (vertical) chairPosition = chairPosition.reverse();

        const isOccupied = Boolean(session);

        return (
            <div
                onClick={() => onClick()}
                className={`relative m-auto transition-transform active:scale-95 ${
                    berhadapan
                        ? vertical
                            ? "h-full w-28 py-4"
                            : "w-full h-28 px-4 pr-6"
                        : !(capacity > 4)
                          ? "h-28 w-28"
                          : vertical
                            ? "h-[calc(100%-4.5rem)] w-28"
                            : "w-[calc(100%-4.5rem)] h-28"
                } flex justify-center items-center`}
            >
                <Activity mode={!berhadapan ? "visible" : "hidden"}>
                    {Array.from({ length: capacity > 4 ? 4 : capacity }).map(
                        (_, i) =>
                            capacity <= 4 ? (
                                <ChairComponent
                                    key={i}
                                    chairPosition={chairPosition}
                                    idx={i}
                                />
                            ) : i > 1 ? (
                                <div
                                    className={`absolute flex justify-evenly ${vertical ? "flex-col items-end h-full" : "flex-row items-start w-full"} ${chairPosition[i]?.split("rounded")?.[0]}`}
                                    key={i}
                                >
                                    {Array.from({
                                        length: capacity / 2 - 1,
                                    }).map((_, j) => (
                                        <ChairComponent
                                            key={j}
                                            many
                                            chairPosition={chairPosition}
                                            idx={i}
                                        />
                                    ))}
                                </div>
                            ) : (
                                <ChairComponent
                                    key={i}
                                    chairPosition={chairPosition}
                                    idx={i}
                                />
                            ),
                    )}
                </Activity>

                <Activity mode={berhadapan ? "visible" : "hidden"}>
                    {Array.from({ length: 2 }).map((_, i) => (
                        <div
                            className={`absolute flex flex-1 justify-evenly ${vertical ? "flex-col items-end h-full" : "flex-row items-start w-full"} ${chairPosition[i + 2]?.split("rounded")?.[0]}`}
                            key={i}
                        >
                            {Array.from({ length: capacity / 2 }).map(
                                (_, j) => (
                                    <ChairComponent
                                        key={j}
                                        many
                                        chairPosition={chairPosition}
                                        idx={i + 2}
                                    />
                                ),
                            )}
                        </div>
                    ))}
                </Activity>

                {/* Body Meja dengan Theme Support */}
                <div
                    className={`flex p-1 relative z-10 w-full h-full rounded-3xl text-sm transition-all duration-500 shadow-xl border-4 ${
                        isDark ? "border-slate-800" : "border-white"
                    } ${
                        isOccupied
                            ? isDark
                                ? "bg-red-600 ring-4 ring-red-900/30"
                                : "bg-red-500 ring-4 ring-red-100"
                            : isDark
                              ? "bg-emerald-600 ring-4 ring-emerald-900/30"
                              : "bg-emerald-500 ring-4 ring-emerald-50"
                    } text-center justify-center items-center font-bold`}
                >
                    <div
                        className={`w-full h-full rounded-[1.4rem] flex items-center justify-center text-white border border-white/10 ${
                            isDark ? "bg-slate-800" : "bg-white"
                        }`}
                    >
                        <div
                            className={`flex flex-col ${!isDark ? "text-slate-800" : "text-white"} leading-tight`}
                        >
                            <span className="text-[8px] uppercase opacity-40">
                                Table
                            </span>
                            <span className="text-base">{tableNumber}</span>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    return (
        <>
            <div
                className={`px-8 py-3 border-b transition-all duration-300 ${isDark ? "bg-slate-900/40 border-white/5" : "bg-gray-50/50 border-gray-200"}`}
            >
                <div className="w-full mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                    <div className="w-full md:w-96">
                        <SearchBar
                            theme={theme}
                            searchItem={newTablesData}
                            searchKeyField="tableNumber"
                            onSelect={(item) => scrollToTable(item)}
                        />
                    </div>

                    {/* Navigation Pill (Identik dengan Kitchen Board) */}
                    <div
                        className={`flex items-center gap-2 p-1.5 rounded-full border shadow-inner ${isDark ? "bg-slate-950 border-white/5" : "bg-gray-100 border-gray-200"}`}
                    >
                        <div className="flex relative bg-transparent rounded-full">
                            {editModeList.map((item) => (
                                <button
                                    key={item.name}
                                    onClick={() => {
                                        setEditMode(item.name);
                                        setDrag(false);
                                    }}
                                    className={`
                      relative px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all duration-300 z-10
                      ${
                          editMode === item.name && !drag
                              ? isDark
                                  ? "text-white"
                                  : "text-gray-800 shadow-sm bg-white"
                              : isDark
                                ? "text-gray-500 hover:text-gray-300"
                                : "text-gray-500 hover:text-gray-700"
                      }
                    `}
                                >
                                    {editMode === item.name &&
                                        !drag &&
                                        isDark && (
                                            <span className="absolute inset-0 bg-slate-800 rounded-full -z-10 border border-white/5 shadow-xl" />
                                        )}
                                    <div className="flex items-center gap-2">
                                        {item.icon} {item.placeholder}
                                    </div>
                                </button>
                            ))}
                        </div>

                        <div
                            className={`w-px h-6 mx-1 ${isDark ? "bg-white/10" : "bg-gray-300"}`}
                        />

                        <button
                            onClick={() => setDrag(!drag)}
                            className={`
                  px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest transition-all
                  ${
                      isDark
                          ? drag
                              ? "bg-indigo-600 text-white shadow-lg shadow-indigo-500/20"
                              : "text-gray-400 hover:bg-slate-800"
                          : drag
                            ? "bg-slate-800 text-white"
                            : "text-gray-600 hover:bg-white"
                  }
                `}
                        >
                            <div className="flex items-center gap-2">
                                <LayoutGrid size={14} /> Drag
                            </div>
                        </button>

                        <div
                            className={`w-px h-6 mx-1 ${isDark ? "bg-white/10" : "bg-gray-300"}`}
                        />

                        {/* <button
                            onClick={() => toggleTheme()}
                            className={`p-2 rounded-full transition-transform active:rotate-90 hover:scale-110 ${isDark ? "text-amber-400 hover:bg-slate-800" : "text-slate-600 hover:bg-white shadow-sm"}`}
                        >
                            {isDark ? <Sun size={16} /> : <Moon size={16} />}
                        </button> */}
                    </div>
                </div>
            </div>
            {/* batas */}
            <div
                className={`absolute bottom-6 left-8 z-10 px-4 py-2.5 rounded-2xl border backdrop-blur-xl shadow-2xl transition-all
            ${isDark ? "bg-slate-900/80 border-white/5 text-gray-400" : "bg-white/80 border-gray-100 text-gray-500"}
          `}
            >
                <div className="flex flex-col gap-1">
                    <div className="flex items-center gap-2">
                        <div
                            className={`w-2 h-2 rounded-full ${isDark ? "bg-amber-500 animate-pulse" : "bg-orange-500"}`}
                        />
                        <span className="text-[10px] font-black uppercase tracking-widest">
                            {drag ? "Positioning Active" : `${editMode} Mode`}
                        </span>
                    </div>
                    <span className="text-[9px] font-bold opacity-50 uppercase tracking-tighter">
                        {table?.length} Registered Tables
                    </span>
                </div>
            </div>
            <div
                ref={mapRef}
                id="table-map"
                className={`relative overflow-auto h-full w-full pb-20 transition-colors duration-500 scrollbar-hide ${
                    isDark
                        ? "bg-slate-950 border-slate-900 shadow-black"
                        : "bg-slate-200/60 border-white shadow-inner"
                }`}
            >
                {isDark && (
                    <div className="fixed inset-0 pointer-events-none opacity-20">
                        <div
                            className={`absolute top-0 left-0 w-125 h-125 ${
                                isDark ? "bg-slate-950 " : "bg-slate-200/60"
                            } rounded-full blur-[120px] mix-blend-screen`}
                        />
                    </div>
                )}

                {/* Grid Pattern Background */}
                {/* <div
        className={`absolute inset-0 pointer-events-none ${isDark ? 'opacity-[0.1]' : 'opacity-[0.1]'}`}
        style={{
          backgroundImage: `radial-gradient(${isDark ? '#fff' : '#000'} 1px, transparent 1px)`,
          backgroundSize: '30px 30px',
        }}
        inset-0
        pointer-ev
      /> */}

                <GridLayout
                    className="layout"
                    gridConfig={{
                        cols: 60,
                        margin: [10, 10],
                        rowHeight:
                            (Math.ceil(
                                (window.innerWidth -
                                    (window.innerWidth * 2.5) / 100) *
                                    5,
                            ) -
                                20 * 60) /
                            60,
                    }}
                    resizeConfig={{ enabled: false }}
                    compactor={noCompactor}
                    layout={layout}
                    width={Math.ceil(
                        (window.innerWidth - (window.innerWidth * 2.5) / 100) *
                            5,
                    )}
                    dragConfig={{ enabled: drag }}
                    onLayoutChange={(newLayout) => {
                        setLayout(newLayout);
                        if (!setNewTablesData) return;
                        setNewTablesData((prev) =>
                            prev.map((t, idx) => {
                                const l = newLayout[idx];
                                return l
                                    ? { ...t, positionX: l.x, positionY: l.y }
                                    : t;
                            }),
                        );
                    }}
                >
                    {layout.map((item) => {
                        const currentTable = newTablesData?.[item.i];
                        return (
                            <div
                                key={item.i}
                                className={`text-white rounded flex flex-col ${drag ? "cursor-grab active:cursor-grabbing" : ""}`}
                            >
                                <TableComponent
                                    session={currentTable?.session?.[0]}
                                    vertical={currentTable.vertical}
                                    berhadapan={currentTable.facing}
                                    capacity={currentTable.capacity}
                                    tableNumber={currentTable.tableNumber}
                                    onClick={() => {
                                        if (onClick) onClick(currentTable);
                                        if (drag) return;
                                        switch (editMode) {
                                            case "information":
                                                window.location.href = `/tables/${currentTable.id}/edit`;

                                                break;
                                            case "type":
                                                if (currentTable.capacity < 4)
                                                    return;
                                                setNewTablesData((prev) =>
                                                    prev.map((t) =>
                                                        t.id === currentTable.id
                                                            ? {
                                                                  ...t,
                                                                  facing: !t.facing,
                                                              }
                                                            : t,
                                                    ),
                                                );
                                                break;
                                            case "rotate":
                                                if (
                                                    currentTable.capacity ==
                                                        4 &&
                                                    !currentTable.facing
                                                )
                                                    return;
                                                setNewTablesData((prev) =>
                                                    prev.map((t) =>
                                                        t.id === currentTable.id
                                                            ? {
                                                                  ...t,
                                                                  vertical:
                                                                      !t.vertical,
                                                              }
                                                            : t,
                                                    ),
                                                );
                                        }
                                    }}
                                />
                            </div>
                        );
                    })}
                </GridLayout>
            </div>
        </>
    );
}
