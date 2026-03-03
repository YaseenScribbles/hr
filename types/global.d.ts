// global.d.ts
declare module "@inertiajs/core" {
  export interface InertiaConfig {
    sharedPageProps: {
      auth: { user: { id: number; name: string } | null };
      appName: string;
    };
    flashDataType: {
      toast?: { type: "success" | "error"; message: string };
    };
    errorValueType: string[];
  }
}
